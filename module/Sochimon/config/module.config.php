<?php
return array(
		'controllers' => array(
				'invokables' => array(
					'Sochimon\Controller\IndexController' => 'Sochimon\Controller\IndexController',
					'Sochimon\Controller\CountriesController' => 'Sochimon\Controller\CountriesController',
						
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
											'action'        => 'index',
									),
							),
							'may_terminate' => true,
							'child_routes' => array(
									'default' => array(
											'type'    => 'Segment',
											'options' => array(
													'route'    => '/[:controller[/:action]]',
													'constraints' => array(
															'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
															'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
													),
													'defaults' => array(
													),
											),
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
