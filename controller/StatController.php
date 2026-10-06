<?php
require_once __DIR__ . '/../modele/StatModel.php';

class StatController {
    private $statModel;

    public function __construct() {
        $this->statModel = new StatModel();
    }

    public function getClientStats() {
        return $this->statModel->getClientStats();
    }

    public function getVoitureStats() {
        return $this->statModel->getVoitureStats();
    }
}
?>
