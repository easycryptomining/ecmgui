<?php

namespace Application\Controller;

use Application\Service\PluginsServiceInterface;
use Application\Service\WalletsServiceInterface;
use Application\Service\SettingsServiceInterface;
use Application\Service\WorkersServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Application\Service\WemosServiceInterface;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use a15lam\PhpWemo;

class IndexController extends AbstractActionController {

    /**
     * @var PluginsServiceInterface
     */
    protected $pluginsService;

    /**
     * @var WalletsServiceInterface
     */
    protected $walletsService;

    /**
     * @var SettingsServiceInterface
     */
    protected $settingsService;

    /**
     * @var WorkersServiceInterface
     */
    protected $workersService;

    /**
     * @var WemosServiceInterface
     */
    protected $wemosService;

    /**
     * Construct
     */
    public function __construct(PluginsServiceInterface $pluginsService, WalletsServiceInterface $walletsService, SettingsServiceInterface $settingsService, WorkersServiceInterface $workersService, WemosServiceInterface $wemosService) {
        $this->pluginsService = $pluginsService;
        $this->walletsService = $walletsService;
        $this->settingsService = $settingsService;
        $this->workersService = $workersService;
        $this->wemosService = $wemosService;
    }

    public function indexAction() {
        $id = 1;
        $currencySymbols = [
            'monero' => 'xmr',
            'ethereum' => 'eth',
            'zcash' => 'zec'
        ];
        $plugins = $this->pluginsService->getPluginsById($id);
        $settings = $this->settingsService->getSettingsById($id);
        $powerCost = $settings->powercost;

        // Wallets
        $wallets = $this->walletsService->getWalletsByFilter();
        foreach ($wallets as $wallet) {
            $walletsArray[] = [
                'id' => $wallet->id,
                'type' => $wallet->type,
                'number' => $wallet->number,
                'balance' => $wallet->balance,
                'pool' => '',
                'poolurl' => '',
                'pending' => '',
            ];
        }

        // Workers
        $workers = $this->workersService->getWorkersByFilter();
        foreach ($workers as $worker) {
            $kwh = '';
            $yearCost = '';
            $monthCost = '';
            $pool = true;

            $workerName = '';
            if (!empty($worker->name)) {
                $workerName = $worker->name;
            } elseif (!empty($worker->ip)) {
                $workerName = $worker->ip;
            } else {
                $workerName = $worker->id;
            }

            // Insight
            if (!empty($worker->insight)) {
                $wemo = $this->wemosService->getWemosById($worker->insight);

                // Get power consomption
                $power = $wemo->power();
                $kwh = round(24 * 365 * ($power / 1000));
                $yearCost = round($kwh * $powerCost);
                $monthCost = round($yearCost / 12);

                // Get power state
                $powerState = $wemo->state();
            }
            $ssh = false;
            if (!empty($worker->sshuser) && !empty($worker->sshpassword) && !empty($worker->sshport)) {
                $ssh = true;
            }

            // Nanopool
            if (preg_match("/nanopool.org/i", $worker->pool)) {
                // Worker status
                $wallet = $this->walletsService->getWalletsById($worker->walletid);
                $content = json_decode(file_get_contents('https://api.nanopool.org/v1/' . $currencySymbols[$wallet->type] . '/workers/' . $wallet->number));
                $nanopoolWorkers = (array) $content->data;
                foreach ($nanopoolWorkers as $nanopoolWorker) {
                    if ($nanopoolWorker->id == $workerName) {
                        if ((time() - $nanopoolWorker->lastShare) > 3600) {
                            $pool = false;
                        }
                    }
                }
                // Wallet pending
                $key = array_search($worker->walletid, array_column($walletsArray, 'id'));
                if ($key !== false) {
                    if (empty($walletsArray[$key]['pool'])) {
                        $walletsArray[$key]['pool'] = 'nanopool';
                        $walletsArray[$key]['poolurl'] = 'https://' . $currencySymbols[$walletsArray[$key]['type']] . '.nanopool.org/account/' . $walletsArray[$key]['number'];
                        $nanopoolBalance = json_decode(file_get_contents('https://api.nanopool.org/v1/' . $currencySymbols[$walletsArray[$key]['type']] . '/balance/' . $walletsArray[$key]['number']));
                        $walletsArray[$key]['pending'] = $nanopoolBalance->data;
                    }
                }
            }

            // Arlo


            $workersArray[] = [
                'id' => $worker->id,
                'name' => $workerName,
                'pool' => $pool,
                'insight' => $worker->insight,
                'software' => $worker->software,
                'softwareport' => $worker->softwareport,
                'amp' => $worker->amp,
                'walletid' => $worker->walletid,
                'ping' => $worker->ping(),
                'kwh' => $kwh,
                'yearcost' => $yearCost,
                'monthcost' => $monthCost,
                'ssh' => $ssh,
                'powerstate' => $powerState
            ];
        }

        // Wemo
        // TODO : only show wemo that are not in workers
        $wemos = $this->wemosService->getWemosByFilter();
        foreach ($wemos as $wemo) {
            $wemosArray[] = [
                'id' => $wemo->id,
                'name' => $wemo->name,
                'type' => $wemo->type,
                'ping' => $wemo->ping(),
                'state' => $wemo->state(),
            ];
        }

        return new ViewModel([
            'title' => 'Dashboard',
            'plugins' => $plugins,
            'walletsArray' => $walletsArray,
            'settings' => $settings,
            'workersArray' => $workersArray,
            'wemosArray' => $wemosArray,
        ]);
    }

    /**
     * @return array
     */
    public function getHashrateAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();

        $response->getHeaders()->addHeaderLine("Content-Type", "application/json");

        // Checks if the request is valid
        if (!$request->isPost() || !$request->isXmlHttpRequest()) {
            $response->setContent(Json::encode(['error' => ['Very bad request']]));
            return $response;
        }

        $url = $request->getPost('url');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        $response->setContent(Json::encode($output));
        return $response;
    }

    /**
     * @return array
     */
    public function getUptimeAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();

        $response->getHeaders()->addHeaderLine("Content-Type", "application/json");

        // Checks if the request is valid
        if (!$request->isPost() || !$request->isXmlHttpRequest()) {
            $response->setContent(Json::encode(['error' => ['Very bad request']]));
            return $response;
        }

        $id = $request->getPost('id');
        $worker = $this->workersService->getWorkersById($id);

        $response->setContent(Json::encode(['uptime' => $worker->uptime()]));
        return $response;
    }

    /**
     * @return array
     */
    public function getTempAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();

        $response->getHeaders()->addHeaderLine("Content-Type", "application/json");

        // Checks if the request is valid
        if (!$request->isPost() || !$request->isXmlHttpRequest()) {
            $response->setContent(Json::encode(['error' => ['Very bad request']]));
            return $response;
        }

        $id = $request->getPost('id');
        $worker = $this->workersService->getWorkersById($id);

        $response->setContent(Json::encode(['temp' => $worker->temp()]));
        return $response;
    }

    /**
     * @return array
     */
    public function getPowerAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();

        $response->getHeaders()->addHeaderLine("Content-Type", "application/json");

        // Checks if the request is valid
        if (!$request->isPost() || !$request->isXmlHttpRequest()) {
            $response->setContent(Json::encode(['error' => ['Very bad request']]));
            return $response;
        }

        $id = $request->getPost('id');
        $wemo = $this->wemosService->getWemosById($id);

        $response->setContent(Json::encode(['power' => $wemo->power()]));
        return $response;
    }

    /**
     * @return array
     */
    public function togglePowerStateAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();

        $response->getHeaders()->addHeaderLine("Content-Type", "application/json");

        // Checks if the request is valid
        if (!$request->isPost() || !$request->isXmlHttpRequest()) {
            $response->setContent(Json::encode(['error' => ['Very bad request']]));
            return $response;
        }

        $id = $request->getPost('id');
        $wemo = $this->wemosService->getWemosById($id);

        $response->setContent(Json::encode(['state' => $wemo->toggle()]));
        return $response;
    }

}
