<?php
declare(strict_types=1);
require_once "../UserRepository.php";

header('Content-Type: application/json');
$userRepo = UserRepository::getInstance();
echo json_encode($userRepo->getUsers());