<?php
  return [

      "app_name" => "Test Application",

      "error_processor" => [
            "not_found" => "error/not_found",
            "server_error" => "error/server"
      ],

      'leaves_per_year' => [
          \Entity\RequestEntity::TYPE['PAID'] => 20,
          \Entity\RequestEntity::TYPE['MEDICAL'] => 5
      ]
  ];