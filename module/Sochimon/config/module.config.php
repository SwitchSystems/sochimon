<?php
return array(
		'console' => [
				'router' => [
						'routes' => [
								'update' => [
										'options' => [
												'route' => 'update',
												'defaults' => [
														'controller' => 'Sochimon\Controller\DataController',
														'action' => 'update'
												]
										]
								]
						]
				]
		],
		'controllers' => array(
				'invokables' => array(
					'Sochimon\Controller\IndexController' => 'Sochimon\Controller\IndexController',
					'Sochimon\Controller\CountriesController' => 'Sochimon\Controller\CountriesController',
					'Sochimon\Controller\DataController' => 'Sochimon\Controller\DataController',

				)
		),


		'router' => array(
				'routes' => array(
					'home' => array(
							'type'    => 'Literal',
							'options' => array(
									'route'    => '/',
									'defaults' => array(
											'controller'    => 'Sochimon\Controller\IndexController',
											'action'        => 'splash',
									),
							),
					),
					'game'	=> array(
							'type'    => 'Literal',
							'options' => array(
									'route'    => '/game',
									'defaults' => array(
											'controller'    => 'Sochimon\Controller\IndexController',
											'action'        => 'index',
									),
							),
					),
			),
		),

		'view_helpers' => array(
				'invokables' => array(
				)
		),

		'service_manager' => array(
				'invokables' => array(
						'DataGrabber' => 'Sochimon\Service\DataGrabber',
				)
		),

		'view_manager' => array(
				'not_found_template'       => 'layout/error',
				'exception_template'       => 'layout/error',
				'template_map' => array(
						'layout/error'            => __DIR__ . '/../view/layout/error.phtml',
				),
				'template_path_stack' => array(
						'sochimon' => __DIR__ . '/../view',
				),
		),

);
