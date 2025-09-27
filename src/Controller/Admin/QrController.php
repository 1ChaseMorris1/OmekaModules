<?php
namespace QRCodes\Controller\Admin;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class QrController extends AbstractActionController
{
    private const SETTINGS_KEY = 'qrcodes_exports';

    public function browseAction()
    {
        $services = $this->getEvent()->getApplication()->getServiceManager();
        $settings = $services->get('Omeka\\Settings');
        $exports = $settings->get(self::SETTINGS_KEY) ?: [];

        if (is_array($exports)) {
            usort($exports, function ($a, $b) {
                return ($b['created'] ?? 0) <=> ($a['created'] ?? 0);
            });
        }

        $view = new ViewModel([
            'exports' => $exports,
        ]);
        $view->setTemplate('q-r-codes/admin/qr/browse');
        return $view;
    }

    public function createAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return $this->redirect()->toRoute('admin/qr-codes');
        }

        $services = $this->getEvent()->getApplication()->getServiceManager();
        $settings = $services->get('Omeka\\Settings');

        $exports = $settings->get(self::SETTINGS_KEY) ?: [];

        $now = time();
        $new = [
            'id' => $this->nextId($exports),
            'created' => $now,
            'label' => 'Export ' . date('Y-m-d H:i:s', $now),
        ];
        $exports[] = $new;

        $settings->set(self::SETTINGS_KEY, $exports);

        $this->messenger()->addSuccess('New export created.');
        return $this->redirect()->toRoute('admin/qr-codes');
    }

    private function nextId(array $exports): int
    {
        $max = 0;
        foreach ($exports as $e) {
            if (isset($e['id']) && (int) $e['id'] > $max) {
                $max = (int) $e['id'];
            }
        }
        return $max + 1;
    }
}

