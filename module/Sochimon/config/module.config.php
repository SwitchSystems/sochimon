<?php
return array(
		'controllers' => array(
				'invokables' => array(
					'Sochimon\Controller\IndexController' => 'Sochimon\Controller\IndexController',
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
		
		'view_manager' => array(
				'template_map' => array(
						
				),
				'template_path_stack' => array(
						'sochimon' => __DIR__ . '/../view',
				),
		),
		
);
