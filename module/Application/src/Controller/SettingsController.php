<?php

namespace Application\Controller;

use Application\Service\PluginsServiceInterface;
use Application\Form\PluginsForm;
use Application\Service\WalletsServiceInterface;
use Zend\Db\Sql\Where;
use Zend\Json\Json;
use Application\Service\SettingsServiceInterface;
use Application\Form\SettingsForm;
use Application\Service\WorkersServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SettingsController extends AbstractActionController {

    /**
     * @var PluginsServiceInterface
     */
    protected $pluginsService;

    /**
     * @var PluginsForm
     */
    protected $pluginsForm;

    /**
     * @var WalletsServiceInterface
     */
    protected $walletsService;

    /**
     * @var settingsServiceInterface
     */
    protected $settingsService;

    /**
     * @var settingsForm
     */
    protected $settingsForm;

    /**
     * @var WorkersServiceInterface
     */
    protected $workersService;

    /**
     * Construct
     */
    public function __construct(PluginsServiceInterface $pluginsService, PluginsForm $pluginsForm, WalletsServiceInterface $walletsService, SettingsServiceInterface $settingsService, SettingsForm $settingsForm, WorkersServiceInterface $workersService) {
        $this->pluginsService = $pluginsService;
        $this->pluginsForm = $pluginsForm;
        $this->walletsService = $walletsService;
        $this->settingsService = $settingsService;
        $this->settingsForm = $settingsForm;
        $this->workersService = $workersService;
    }

    public function indexAction() {
        // Plugins entities
        // only the first line as it should have only one
        $id = 1;
        $pluginsEntities = $this->pluginsService->getPluginsById($id);
        $pluginsFormEntities = $this->pluginsForm;
        // Plugins form
        $pluginsData = array(
            'id' => $pluginsEntities->id,
            'arlo' => $pluginsEntities->arlo,
            'wemo' => $pluginsEntities->wemo,
            'hue' => $pluginsEntities->hue,
            'crypto' => $pluginsEntities->crypto,
        );
        $pluginsFormEntities->setData($pluginsData);

        // Settings entities
        // only the first line as it should have only one
        $settingsEntities = $this->settingsService->getSettingsById($id);
        $settingsFormEntities = $this->settingsForm;
        // Settings form
        $settingsData = array(
            'id' => $settingsEntities->id,
            'arlousername' => $settingsEntities->arlousername,
            'arlopassword' => $settingsEntities->arlopassword,
            'refresh' => $settingsEntities->refresh,
            'powercost' => $settingsEntities->powercost,
        );
        $settingsFormEntities->setData($settingsData);
        
        // Wallets entities
        $walletsEntities = $this->walletsService->getWalletsByFilter();
        $walletsData = $walletsEntities->toArray();

        return new ViewModel([
            'title' => 'Settings',
            'pluginsForm' => $pluginsFormEntities,
            'settingsForm' => $settingsFormEntities,
            'walletsData' => $walletsData,
        ]);
    }

    /**
     * FORM : Update plugins
     * 
     * @return redirect
     */
    public function updatePluginsAction() {
        // Plugins have only one line
        $id = 1;
        $pluginsFormEntities = $this->pluginsForm;

        // Form submitted ?
        if ($this->getRequest()->isPost()) {
            // Fill the form with POST data
            $data = $this->params()->fromPost();
            $pluginsFormEntities->setData($data);

            // Save to DB if valid form
            if ($pluginsFormEntities->isValid()) {
                // Delete csrf and submit button from data
                // TODO : find how to auto remove
                unset($data['csrf']);
                unset($data['submit']);

                if ($this->pluginsService->updatePlugins($data, ['id' => $id])) {
                    $this->flashMessenger()->addSuccessMessage('Plugins updated');
                } else {
                    $this->flashMessenger()->addErrorMessage('Update failed');
                }
                // Redirect to plugins page
                return $this->redirect()->toRoute('settings', ['action' => 'index']);
            }
        }
    }

    /**
     * FORM : Update settings
     * 
     * @return redirect
     */
    public function updateSettingsAction() {
        // Plugins have only one line
        $id = 1;
        $settingsFormEntities = $this->settingsForm;

        // Form submitted ?
        if ($this->getRequest()->isPost()) {
            // Fill the form with POST data
            $data = $this->params()->fromPost();
            $settingsFormEntities->setData($data);

            // Save to DB if valid form
            if ($settingsFormEntities->isValid()) {
                // Delete csrf and submit button from data
                // TODO : find how to auto remove
                unset($data['csrf']);
                unset($data['submit']);

                if ($this->settingsService->updateSettings($data, ['id' => $id])) {
                    $this->flashMessenger()->addSuccessMessage('Settings updated');
                } else {
                    $this->flashMessenger()->addErrorMessage('Update failed');
                }
                // Redirect to plugins page
                return $this->redirect()->toRoute('settings', ['action' => 'index']);
            }
        }
    }

    /**
     * AJAX : call to get all wallets
     * 
     * @return array
     */
    public function getAllWalletsAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine("Content-Type", "application/json");

        // Checks if the request is valid
        if (!$request->isPost() || !$request->isXmlHttpRequest()) {
            $response->setContent(Json::encode(['error' => ['Very bad request']]));
            return $response;
        }

        // Gets wallets data
        $data = $this->formatWalletsToDatatable();

        $response->setContent(Json::encode($data));
        return $response;
    }

    /**
     * Prepares formatted data for datatable
     * 
     * @return array
     */
    protected function formatWalletsToDatatable() {
        $request = $this->getRequest();

        // Gets values for datatable from the request
        $draw = intval($request->getPost('draw'));
        $limit = intval($request->getPost('length'));
        $offset = intval($request->getPost('start'));

        // Prepares Where clause
        $search = strip_tags($request->getPost('search')['value']);
        if (!empty($search)) {
            // Where clause using closure
            $where = function(Where $where) use($search) {
                $where->like('type', "%$search%")
                ->or->like('number', "%$search%")
                ->or->like('id', "%$search%");
            };
        } else {
            $where = null;
        }

        // Prepare value for ORDER BY clause if provided
        $order = $request->getPost('order');
        if (!empty($order)) {
            $columns = [
                0 => 'id',
                1 => 'type',
                2 => 'number',
                3 => 'balance',
            ];
            $columnNumber = intval($request->getPost('order')['0']['column']);
            // $column = strval($request->getPost('columns')[$columnNumber]['data']);
            $column = $columns[$columnNumber];
            $dir = strval($request->getPost('order')['0']['dir']);
            $orderBy = "$column $dir";
        } else {
            $orderBy = "id asc";
        }

        // Gets filtered data
        // Wallets entities
        $walletsEntities = $this->walletsService->getWalletsByFilter($where, $orderBy, $limit, $offset);
        $walletsData = $walletsEntities->toArray();

        // Prepares data for datatable
        $tableContent = [];
        foreach ($walletsData as $wallets) {
            $prepareData = [];
            $prepareData[] = $wallets['id'];
            $prepareData[] = $wallets['type'];
            $prepareData[] = $wallets['number'];
            $prepareData[] = $wallets['balance'];
            $prepareData[] = sprintf('<span class="glyphicon glyphicon-edit dt-action-button" id="editWallet" aria-hidden="true" data-walletid="%d"></span>', $wallets['id']);
            $prepareData[] = sprintf('<span class="glyphicon glyphicon-trash dt-action-button" aria-hidden="true" data-walletid="%d" data-toggle="modal" data-target="#deleteWalletModal"></span>', $wallets['id']);
            $tableContent[] = $prepareData;
        }

        // Makes appropriate total for pagination
        if (null === $where) {
            $allWallets = $this->walletsService->getWalletsByFilter();
            $recordsTotal = count($allWallets);
            $recordsFiltered = $recordsTotal;
        } elseif (null !== $where) {
            $walletsEntities = $this->walletsService->getWalletsByFilter($where);
            $recordsTotal = count($walletsEntities->toArray());
            $recordsFiltered = $recordsTotal;
        }

        // Prepares data available for datatable
        $data = [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $tableContent,
        ];

        return $data;
    }

    /**
     * AJAX : call to get one wallet info
     * 
     * @return array
     */
    public function getWalletAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine("Content-Type", "application/json");

        // Checks if the request is valid
        if (!$request->isPost() || !$request->isXmlHttpRequest()) {
            $response->setContent(Json::encode(['error' => ['Very bad request']]));
            return $response;
        }

        $walletId = intval($request->getPost('walletId'));
        $walletDetails = $this->walletsService->getWalletsById($walletId);
        if ($walletDetails) {
            $response->setContent(Json::encode(['wallet' => $walletDetails]));
        } else {
            $response->setContent(Json::encode(['error' => ['Wallet not found']]));
        }

        return $response;
    }

    /**
     * AJAX : call to add a wallet
     * 
     * @return array
     */
    public function addWalletAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine("Content-Type", "application/json");

        // Checks if the request is valid
        if (!$request->isPost() || !$request->isXmlHttpRequest()) {
            $response->setContent(Json::encode(['error' => ['Very bad request']]));
            return $response;
        }

        $data = $request->getPost()->toArray();
        $walletId = (int) $data['walletId'];
        unset($data['walletId']);

        if ($walletId = $this->walletsService->insertWallets($data)) {
            $response->setContent(Json::encode(['success' => 'Wallet created', 'walletId' => $walletId]));
        } else {
            $response->setContent(Json::encode(['error' => ['Could not create wallet']]));
        }

        return $response;
    }

    /**
     * AJAX : call to update a wallet
     * 
     * @return array
     */
    public function updWalletAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine("Content-Type", "application/json");

        // Checks if the request is valid
        if (!$request->isPost() || !$request->isXmlHttpRequest()) {
            $response->setContent(Json::encode(['error' => ['Very bad request']]));
            return $response;
        }
        $rawWalletId = $request->getPost('walletId');
        $walletId = (int) $rawWalletId;

        if (!empty($walletId) && is_int($walletId)) {
            // Gets the data and wallets ID
            $data = $request->getPost()->toArray();
            $walletId = (int) $data['walletId'];
            unset($data['walletId']);

            if ($this->walletsService->updateWallets($data, ['id' => $walletId])) {
                $response->setContent(Json::encode(['success' => 'Wallet updated']));
            } else {
                $response->setContent(Json::encode(['error' => ['Change any data and try again']]));
            }
        } else {
            $response->setContent(Json::encode(['error' => ['No wallet id provided']]));
        }

        return $response;
    }

    /**
     * AJAX : call to delete a wallet
     * 
     * @return array
     */
    public function delWalletAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine("Content-Type", "application/json");

        // Checks if the request is valid
        if (!$request->isPost() || !$request->isXmlHttpRequest()) {
            $response->setContent(Json::encode(['error' => ['Very bad request']]));
            return $response;
        }

        $walletId = intval($request->getPost('walletId'));
        if ($this->walletsService->deleteWallets(['id' => $walletId])) {
            $response->setContent(Json::encode(['success' => "Wallet deleted"]));
        } else {
            $response->setContent(Json::encode(['error' => ['Could not find the wallet']]));
        }

        return $response;
    }

    /**
     * AJAX : call to get all workers
     * 
     * @return array
     */
    public function getAllWorkersAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine("Content-Type", "application/json");

        // Checks if the request is valid
        if (!$request->isPost() || !$request->isXmlHttpRequest()) {
            $response->setContent(Json::encode(['error' => ['Very bad request']]));
            return $response;
        }

        // Gets workerss data
        $data = $this->formatWorkersToDatatable();

        $response->setContent(Json::encode($data));
        return $response;
    }

    /**
     * Prepares formatted data for datatable
     * 
     * @return array
     */
    protected function formatWorkersToDatatable() {
        $request = $this->getRequest();

        // Gets values for datatable from the request
        $draw = intval($request->getPost('draw'));
        $limit = intval($request->getPost('length'));
        $offset = intval($request->getPost('start'));

        // Prepares Where clause
        $search = strip_tags($request->getPost('search')['value']);
        if (!empty($search)) {
            // Where clause using closure
            $where = function(Where $where) use($search) {
                $where->like('name', "%$search%")
                ->or->like('ip', "%$search%")
                ->or->like('pool', "%$search%")
                ->or->like('insight', "%$search%")
                ->or->like('software', "%$search%")
                ->or->like('softwareport', "%$search%");
            };
        } else {
            $where = null;
        }

        // Prepare value for ORDER BY clause if provided
        $order = $request->getPost('order');
        if (!empty($order)) {
            $columns = [
                0 => 'id',
                1 => 'name',
                2 => 'ip',
                3 => 'pool',
                4 => 'insight',
                5 => 'software',
                6 => 'softwareport',
                7 => 'amp',
                8 => 'walletid',
            ];
            $columnNumber = intval($request->getPost('order')['0']['column']);
            // $column = strval($request->getPost('columns')[$columnNumber]['data']);
            $column = $columns[$columnNumber];
            $dir = strval($request->getPost('order')['0']['dir']);
            $orderBy = "$column $dir";
        } else {
            $orderBy = "id asc";
        }

        // Gets filtered data
        // Workers entities
        $workersEntities = $this->workersService->getWorkersByFilter($where, $orderBy, $limit, $offset);
        $workersData = $workersEntities->toArray();

        // Prepares data for datatable
        $tableContent = [];
        foreach ($workersData as $workers) {
            $prepareData = [];
            $prepareData[] = $workers['id'];
            $prepareData[] = $workers['name'];
            $prepareData[] = $workers['ip'];
            $prepareData[] = $workers['pool'];
            $prepareData[] = $workers['insight'];
            $prepareData[] = $workers['software'];
            $prepareData[] = $workers['softwareport'];
            $prepareData[] = $workers['amp'];
            $prepareData[] = $workers['walletid'];
            $prepareData[] = $workers['sshuser'];
            $prepareData[] = $workers['sshpassword'];
            $prepareData[] = $workers['sshport'];
            $prepareData[] = sprintf('<span class="glyphicon glyphicon-edit dt-action-button" id="editWorker" aria-hidden="true" data-workerid="%d"></span>', $workers['id']);
            $prepareData[] = sprintf('<span class="glyphicon glyphicon-trash dt-action-button" aria-hidden="true" data-workerid="%d" data-toggle="modal" data-target="#deleteWorkerModal"></span>', $workers['id']);
            $tableContent[] = $prepareData;
        }

        // Makes appropriate total for pagination
        if (null === $where) {
            $allWorkers = $this->workersService->getWorkersByFilter();
            $recordsTotal = count($allWorkers);
            $recordsFiltered = $recordsTotal;
        } elseif (null !== $where) {
            $workersEntities = $this->workersService->getWorkersByFilter($where);
            $recordsTotal = count($workersEntities->toArray());
            $recordsFiltered = $recordsTotal;
        }

        // Prepares data available for datatable
        $data = [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $tableContent,
        ];

        return $data;
    }
    
    /**
     * AJAX : call to get one worker info
     * 
     * @return array
     */
    public function getWorkerAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine("Content-Type", "application/json");

        // Checks if the request is valid
        if (!$request->isPost() || !$request->isXmlHttpRequest()) {
            $response->setContent(Json::encode(['error' => ['Very bad request']]));
            return $response;
        }

        $workerId = intval($request->getPost('workerId'));
        $workerDetails = $this->workersService->getWorkersById($workerId);
        if ($workerDetails) {
            $response->setContent(Json::encode(['worker' => $workerDetails]));
        } else {
            $response->setContent(Json::encode(['error' => ['Worker not found']]));
        }

        return $response;
    }

    /**
     * AJAX : call to add a worker
     * 
     * @return array
     */
    public function addWorkerAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine("Content-Type", "application/json");

        // Checks if the request is valid
        if (!$request->isPost() || !$request->isXmlHttpRequest()) {
            $response->setContent(Json::encode(['error' => ['Very bad request']]));
            return $response;
        }

        $data = $request->getPost()->toArray();
        $workerId = (int) $data['workerId'];
        unset($data['workerId']);

        if ($workerId = $this->workersService->insertWorkers($data)) {
            $response->setContent(Json::encode(['success' => 'Worker created', 'workerId' => $workerId]));
        } else {
            $response->setContent(Json::encode(['error' => ['Could not create worker']]));
        }

        return $response;
    }

    /**
     * AJAX : call to update a worker
     * 
     * @return array
     */
    public function updWorkerAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine("Content-Type", "application/json");

        // Checks if the request is valid
        if (!$request->isPost() || !$request->isXmlHttpRequest()) {
            $response->setContent(Json::encode(['error' => ['Very bad request']]));
            return $response;
        }
        $rawWorkerId = $request->getPost('workerId');
        $workerId = (int) $rawWorkerId;

        if (!empty($workerId) && is_int($workerId)) {
            // Gets the data and workers ID
            $data = $request->getPost()->toArray();
            $workerId = (int) $data['workerId'];
            unset($data['workerId']);

            if ($this->workersService->updateWorkers($data, ['id' => $workerId])) {
                $response->setContent(Json::encode(['success' => 'Worker updated']));
            } else {
                $response->setContent(Json::encode(['error' => ['Change any data and try again']]));
            }
        } else {
            $response->setContent(Json::encode(['error' => ['No worker id provided']]));
        }

        return $response;
    }

    /**
     * AJAX : call to delete a worker
     * 
     * @return array
     */
    public function delWorkerAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine("Content-Type", "application/json");

        // Checks if the request is valid
        if (!$request->isPost() || !$request->isXmlHttpRequest()) {
            $response->setContent(Json::encode(['error' => ['Very bad request']]));
            return $response;
        }

        $workerId = intval($request->getPost('workerId'));
        if ($this->workersService->deleteWorkers(['id' => $workerId])) {
            $response->setContent(Json::encode(['success' => "Worker deleted"]));
        } else {
            $response->setContent(Json::encode(['error' => ['Could not find the worker']]));
        }

        return $response;
    }

}
