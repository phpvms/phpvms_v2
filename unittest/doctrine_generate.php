<?php

include '../core/codon.config.php';

Doctrine::generateModelsFromDb(DOCTRINE_MODELS, array('doctrine'), array('generateTableClasses' => true));