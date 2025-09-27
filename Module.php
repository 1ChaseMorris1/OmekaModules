<?php
namespace QRCodes;

use Omeka\Module\AbstractModule;
use Laminas\EventManager\Event;
use Laminas\EventManager\SharedEventManagerInterface;

class Module extends AbstractModule
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function attachListeners(SharedEventManagerInterface $sharedEventManager)
    {
        // Add a "QR Codes" tab on admin item pages (add, edit, show)
        $sharedEventManager->attach(
            'Omeka\\Controller\\Admin\\Item',
            'view.add.section_nav',
            [$this, 'addQrTab']
        );
        $sharedEventManager->attach(
            'Omeka\\Controller\\Admin\\Item',
            'view.edit.section_nav',
            [$this, 'addQrTab']
        );
        $sharedEventManager->attach(
            'Omeka\\Controller\\Admin\\Item',
            'view.show.section_nav',
            [$this, 'addQrTab']
        );

        // Render the tab content on admin item pages
        $sharedEventManager->attach(
            'Omeka\\Controller\\Admin\\Item',
            'view.add.form.after',
            [$this, 'renderItemSection']
        );
        $sharedEventManager->attach(
            'Omeka\\Controller\\Admin\\Item',
            'view.edit.form.after',
            [$this, 'renderItemSection']
        );
        $sharedEventManager->attach(
            'Omeka\\Controller\\Admin\\Item',
            'view.show.after',
            [$this, 'renderItemSection']
        );
    }

    public function addQrTab(Event $event): void
    {
        $view = $event->getTarget();
        $sectionNav = $event->getParam('section_nav') ?: [];
        $sectionNav['qrcodes-section'] = $view->translate('QR Codes');
        $event->setParam('section_nav', $sectionNav);
    }

    public function renderItemSection(Event $event): void
    {
        echo $event->getTarget()->partial('common/qrcodes-item-section');
    }
}
