<?php
namespace Lightwerk\DbalUtility\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Markus Blaschke <typo3@markus-blaschke.de> (dbal_utility)
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * TYPO3 backend module for dbal_utility
 *
 * @package     TYPO3
 * @subpackage  dbal_utility
 */
class BackendController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    /**
     * List log files
     */
    public function mainAction() {
        // Check context
        if (!\TYPO3\CMS\Core\Utility\GeneralUtility::getApplicationContext()->isDevelopment()) {
            $this->addFlashMessage('Query logging only available in DEVELOPMENT Context', '' , \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);
        }

        // Expire old log files
        \Lightwerk\DbalUtility\Service\RequestLogService::expireLogFiles();

        // Get list of sorted log files
        $logFileList = \Lightwerk\DbalUtility\Service\RequestLogService::getLogFileList();

        $this->view->assign('LogList', $logFileList);
    }

    /**
     * Show log entry
     *
     * @param string $log Log id
     */
    public function showAction($log) {
        try {
            // Try to open and read logfile
            $log = $this->objectManager->get('Lightwerk\\DbalUtility\\Log\\LogFile', $log);

            $this->view->assign('Log', $log);
        } catch (\Exception $e) {
            $this->addFlashMessage('Could not find log', null, \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);

            $this->redirect('main');
        }
    }

}