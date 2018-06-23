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
        $pluginsEntities = $this->pluginsService->getPluginsById($id);
        $settingsEntities = $this->settingsService->getSettingsById($id);
        $powerCost = $settingsEntities->powercost;

        $walletsEntities = $this->walletsService->getWalletsByFilter();
        foreach ($walletsEntities as $wallet) {
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

        $workersEntities = $this->workersService->getWorkersByFilter();
        foreach ($workersEntities as $worker) {
            $kwh = '';
            $yearCost = '';
            $monthCost = '';
            $insight = false;
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
                $insight = true;
                $device = \a15lam\PhpWemo\Discovery::lookupDevice('id', $worker->insight);
                if (!empty($device)) {
                    $client = new \a15lam\PhpWemo\WemoClient($device['ip'], $device['port']);
                    $deviceClass = $device['class_name'];
                    $myDevice = new $deviceClass($device['id'], $client);
                    // Get power consomption
                    $params = $myDevice->getParams();
                    $parts = explode('|', $params);
                    $power = round($parts[7] / 1000);
                    $kwh = round(24 * 365 * ($power / 1000));
                    $yearCost = round($kwh * $powerCost);
                    $monthCost = round($yearCost / 12);

                    // Get power state
                    $powerState = $myDevice->state();
                }
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

            $workersArray[] = [
                'id' => $worker->id,
                'name' => $workerName,
                'pool' => $pool,
                'insight' => $insight,
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

        $wemosEntities = $this->wemosService->getWemosByFilter();
        foreach ($wemosEntities as $wemo) {
            $device = \a15lam\PhpWemo\Discovery::lookupDevice('id', $wemo->name);
            if (!empty($device)) {
                $client = new \a15lam\PhpWemo\WemoClient($device['ip'], $device['port']);
                $deviceClass = $device['class_name'];
                $myDevice = new $deviceClass($device['id'], $client);
                // Get power state
                $state = $myDevice->state();
            }
            $wemosArray[] = [
                'id' => $wemo->id,
                'name' => $wemo->name,
                'type' => $wemo->type,
                'ping' => $wemo->ping(),
                'state' => $state,
            ];
        }

        return new ViewModel([
            'title' => 'Dashboard',
            'plugins' => $pluginsEntities,
            'walletsArray' => $walletsArray,
            'settings' => $settingsEntities,
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
        $workerName = '';
        if (!empty($worker->name)) {
            $workerName = $worker->name;
        } elseif (!empty($worker->ip)) {
            $workerName = $worker->ip;
        } else {
            $response->setContent(Json::encode(['error' => ['Worker must have a name or an ip']]));
            return $response;
        }

        $uptime = '-';
        $connection = ssh2_connect($workerName, $worker->sshport);
        if (ssh2_auth_password($connection, $worker->sshuser, $worker->sshpassword)) {
            $stream = ssh2_exec($connection, "uptime -p");
            stream_set_blocking($stream, true);
            $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
            $uptime = stream_get_contents($stream_out);
            $uptime = str_replace('up ', '', $uptime);
            $uptime = str_replace('years', 'Y', $uptime);
            $uptime = str_replace('year', 'Y', $uptime);
            $uptime = str_replace('mounths', 'M', $uptime);
            $uptime = str_replace('mounth', 'M', $uptime);
            $uptime = str_replace('weeks', 'W', $uptime);
            $uptime = str_replace('week', 'W', $uptime);
            $uptime = str_replace('days', 'd', $uptime);
            $uptime = str_replace('day', 'd', $uptime);
            $uptime = str_replace('hours', 'h', $uptime);
            $uptime = str_replace('hour', 'h', $uptime);
            $uptime = str_replace('minutes', 'm', $uptime);
            $uptime = str_replace('minute', 'm', $uptime);
            $uptime = str_replace('seconds', 's', $uptime);
            $uptime = str_replace('second', 's', $uptime);
            $uptime = str_replace(' ', '', $uptime);
            $uptime = str_replace(',', ' ', $uptime);
            $uptime = str_replace(PHP_EOL, '', $uptime);
        }

        $response->setContent(Json::encode(['uptime' => $uptime]));
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
        $workerName = '';
        if (!empty($worker->name)) {
            $workerName = $worker->name;
        } elseif (!empty($worker->ip)) {
            $workerName = $worker->ip;
        } else {
            $response->setContent(Json::encode(['error' => ['Worker must have a name or an ip']]));
            return $response;
        }

        $temp = '-';
        $connection = ssh2_connect($workerName, $worker->sshport);
        if (ssh2_auth_password($connection, $worker->sshuser, $worker->sshpassword)) {
            $stream = ssh2_exec($connection, "sensors");
            stream_set_blocking($stream, true);
            $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
            $sensors = stream_get_contents($stream_out);

            if (trim($sensors) !== "") {
                $sensors = str_replace(":\n", ":", $sensors);
                $sensors = str_replace("\n\n", "\n", $sensors);
                $lines = preg_split("/\n/", $sensors, -1, PREG_SPLIT_NO_EMPTY);
            }
            $matcheKey = array_keys(preg_grep('/^coretemp/i', $lines));
            $strinToParse = $lines[$matcheKey[0] + 2];
            $data = explode(" ", $strinToParse);
            $coreTemp = str_replace('+', '', $data[4]);
            $temp = "Cpu: $coreTemp °C";

            $matcheKey = array_keys(preg_grep('/^amdgpu/i', $lines));
            if (!empty($matcheKey)) {
                $i = 1;
                foreach ($matcheKey as $key) {
                    $strinToParse = $lines[$key + 3];
                    $data = explode(" ", $strinToParse);
                    $gpuTemp = str_replace('+', '', $data[8]);
                    $temp .= "<br />Gpu$i: $gpuTemp °C";
                    $i++;
                }
            } else {
                $stream = ssh2_exec($connection, "nvidia-smi --query-gpu=temperature.gpu --format=csv,noheader");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $sensors = trim(stream_get_contents($stream_out));
                $data = explode(PHP_EOL, $sensors);

                $i = 1;
                foreach ($data as $value) {
                    $temp .= "<br />Gpu$i: $value °C";
                    $i++;
                }
            }
        }

        $response->setContent(Json::encode(['temp' => $temp]));
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
        $worker = $this->workersService->getWorkersById($id);
        $insight = $worker->insight;

        $power = '-';
        $device = \a15lam\PhpWemo\Discovery::lookupDevice('id', $insight);
        if (!empty($device)) {
            $client = new \a15lam\PhpWemo\WemoClient($device['ip'], $device['port']);
            $deviceClass = $device['class_name'];
            $myDevice = new $deviceClass($device['id'], $client);
            $params = $myDevice->getParams();
            $parts = explode('|', $params);
            $power = round($parts[7] / 1000);
        }

        $response->setContent(Json::encode(['power' => $power]));
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
        $state = $request->getPost('state');
        $entity = $request->getPost('entity');

        if ($entity == 'worker') {
            $worker = $this->workersService->getWorkersById($id);
            $wemoId = $worker->insight;
        } elseif ($entity == 'wemo') {
            $wemo = $this->wemosService->getWemosById($id);
            $wemoId = $wemo->name;
        }

        $device = \a15lam\PhpWemo\Discovery::lookupDevice('id', $wemoId);
        if (!empty($device)) {
            $client = new \a15lam\PhpWemo\WemoClient($device['ip'], $device['port']);
            $deviceClass = $device['class_name'];
            $myDevice = new $deviceClass($device['id'], $client);
            // Don't use real true/false or it fail
            if ($state == 'true') {
                $myDevice->On();
            } else {
                $myDevice->Off();
            }
            $state = $myDevice->state();
        }

        $response->setContent(Json::encode(['state' => $state]));
        return $response;
    }

}
