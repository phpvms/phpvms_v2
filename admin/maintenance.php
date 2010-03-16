<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */


/*	This is the maintenance cron file, which can run nightly. 
	You should either point to this file directly in your web-host's control panel
	Or add an entry into the crontab file. I recommend running this maybe 2-3am, 
 */

include dirname(dirname(__FILE__)).'/core/codon.config.php';

/* Find any retired pilots and set them to retired */
PilotData::findRetiredPilots();

/* Update any expenses */
FinanceData::updateAllExpenses();

/* Send any PIREPs to vaCentral which haven't been exported */