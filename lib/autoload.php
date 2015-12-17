<?php
    function autoload($class)
    {
        // Exemple de liste de classes que contiendra notre autoload
        $classes = array (

		  //LIBRARY
		  'application' => 'lib/Application.class.php',
		  'applicationcomponent' => 'lib/ApplicationComponent.class.php',
		  'backcontroller' => 'lib/BackController.class.php',
		  'tipkin\config' => 'lib/Config.class.php',
		  'httprequest' => 'lib/HTTPRequest.class.php',
		  'httpresponse' => 'lib/HTTPResponse.class.php',
		  'manager' => 'lib/Manager.class.php',
		  'managers' => 'lib/Managers.class.php',
		  'mediaimage' => 'lib/MediaImage.class.php',
		  'messagebox' => 'lib/MessageBox.class.php',
		  'page' => 'lib/Page.class.php',
		  'pdofactory' => 'lib/PDOFactory.class.php',
		  'record' => 'lib/Record.class.php',
		  'router' => 'lib/Router.class.php',
		  'smarty' => 'lib/smarty/libs/Smarty.class.php',
		  'smartybc' => 'lib/smarty/libs/SmartyBC.class.php',
		  'user' => 'lib/User.class.php',
		  'simpleimage' => 'lib/SimpleImage.class.php',
		  'mail' => 'lib/Mail.class.php',

		  //SMARTY
		  'smarty' => 'lib/smarty/libs/Smarty.class.php',
		  'smarty_cacheresource' => 'lib/smarty/libs/sysplugins/smarty_cacheresource.php',
		  'smarty_cacheresource_custom' => 'lib/smarty/libs/sysplugins/smarty_cacheresource_custom.php',
		  'smarty_cacheresource_keyvaluestore' => 'lib/smarty/libs/sysplugins/smarty_cacheresource_keyvaluestore.php',
		  'smarty_config_source' => 'lib/smarty/libs/sysplugins/smarty_config_source.php',
		  'smarty_internal_cacheresource_file' => 'lib/smarty/libs/sysplugins/smarty_internal_cacheresource_file.php',
		  'smarty_internal_compilebase' => 'lib/smarty/libs/sysplugins/smarty_internal_compilebase.php',
		  'smarty_internal_compile_append' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_append.php',
		  'smarty_internal_compile_assign' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_assign.php',
		  'smarty_internal_compile_block' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_block.php',
		  'smarty_internal_compile_break' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_break.php',
		  'smarty_internal_compile_call' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_call.php',
		  'smarty_internal_compile_capture' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_capture.php',
		  'smarty_internal_compile_config_load' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_config_load.php',
		  'smarty_internal_compile_continue' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_continue.php',
		  'smarty_internal_compile_debug' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_debug.php',
		  'smarty_internal_compile_eval' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_eval.php',
		  'smarty_internal_compile_extends' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_extends.php',
		  'smarty_internal_compile_for' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_for.php',
		  'smarty_internal_compile_foreach' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_foreach.php',
		  'smarty_internal_compile_function' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_function.php',
		  'smarty_internal_compile_if' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_if.php',
		  'smarty_internal_compile_include' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_include.php',
		  'smarty_internal_compile_include_php' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_include_php.php',
		  'smarty_internal_compile_insert' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_insert.php',
		  'smarty_internal_compile_ldelim' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_ldelim.php',
		  'smarty_internal_compile_nocache' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_nocache.php',
		  'smarty_internal_compile_private_block_plugin' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_private_block_plugin.php',
		  'smarty_internal_compile_private_function_plugin' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_private_function_plugin.php',
		  'smarty_internal_compile_private_modifier' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_private_modifier.php',
		  'smarty_internal_compile_private_object_block_function' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_private_object_block_function.php',
		  'smarty_internal_compile_private_object_function' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_private_object_function.php',
		  'smarty_internal_compile_private_print_expression' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_private_print_expression.php',
		  'smarty_internal_compile_private_registered_block' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_private_registered_block.php',
		  'smarty_internal_compile_private_registered_function' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_private_registered_function.php',
		  'smarty_internal_compile_private_special_variable' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_private_special_variable.php',
		  'smarty_internal_compile_rdelim' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_rdelim.php',
		  'smarty_internal_compile_section' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_section.php',
		  'smarty_internal_compile_setfilter' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_setfilter.php',
		  'smarty_internal_compile_while' => 'lib/smarty/libs/sysplugins/smarty_internal_compile_while.php',
		  'smarty_internal_config' => 'lib/smarty/libs/sysplugins/smarty_internal_config.php',
		  'smarty_internal_configfilelexer' => 'lib/smarty/libs/sysplugins/smarty_internal_configfilelexer.php',
		  'smarty_internal_configfileparser' => 'lib/smarty/libs/sysplugins/smarty_internal_configfileparser.php',
		  'smarty_internal_config_file_compiler' => 'lib/smarty/libs/sysplugins/smarty_internal_config_file_compiler.php',
		  'smarty_internal_data' => 'lib/smarty/libs/sysplugins/smarty_internal_data.php',
		  'smarty_internal_debug' => 'lib/smarty/libs/sysplugins/smarty_internal_debug.php',
		  'smarty_internal_filter_handler' => 'lib/smarty/libs/sysplugins/smarty_internal_filter_handler.php',
		  'smarty_internal_function_call_handler' => 'lib/smarty/libs/sysplugins/smarty_internal_function_call_handler.php',
		  'smarty_internal_get_include_path' => 'lib/smarty/libs/sysplugins/smarty_internal_get_include_path.php',
		  'smarty_internal_nocache_insert' => 'lib/smarty/libs/sysplugins/smarty_internal_nocache_insert.php',
		  'smarty_internal_parsetree' => 'lib/smarty/libs/sysplugins/smarty_internal_parsetree.php',
		  'smarty_internal_resource_eval' => 'lib/smarty/libs/sysplugins/smarty_internal_resource_eval.php',
		  'smarty_internal_resource_extends' => 'lib/smarty/libs/sysplugins/smarty_internal_resource_extends.php',
		  'smarty_internal_resource_file' => 'lib/smarty/libs/sysplugins/smarty_internal_resource_file.php',
		  'smarty_internal_resource_php' => 'lib/smarty/libs/sysplugins/smarty_internal_resource_php.php',
		  'smarty_internal_resource_registered' => 'lib/smarty/libs/sysplugins/smarty_internal_resource_registered.php',
		  'smarty_internal_resource_stream' => 'lib/smarty/libs/sysplugins/smarty_internal_resource_stream.php',
		  'smarty_internal_resource_string' => 'lib/smarty/libs/sysplugins/smarty_internal_resource_string.php',
		  'smarty_internal_smartytemplatecompiler' => 'lib/smarty/libs/sysplugins/smarty_internal_smartytemplatecompiler.php',
		  'smarty_internal_template' => 'lib/smarty/libs/sysplugins/smarty_internal_template.php',
		  'smarty_internal_templatebase' => 'lib/smarty/libs/sysplugins/smarty_internal_templatebase.php',
		  'smarty_internal_templatecompilerbase' => 'lib/smarty/libs/sysplugins/smarty_internal_templatecompilerbase.php',
		  'smarty_internal_templatelexer' => 'lib/smarty/libs/sysplugins/smarty_internal_templatelexer.php',
		  'smarty_internal_templateparser' => 'lib/smarty/libs/sysplugins/smarty_internal_templateparser.php',
		  'smarty_internal_utility' => 'lib/smarty/libs/sysplugins/smarty_internal_utility.php',
		  'smarty_internal_write_file' => 'lib/smarty/libs/sysplugins/smarty_internal_write_file.php',
		  'smarty_resource' => 'lib/smarty/libs/sysplugins/smarty_resource.php',
		  'smarty_resource_custom' => 'lib/smarty/libs/sysplugins/smarty_resource_custom.php',
		  'smarty_resource_recompiled' => 'lib/smarty/libs/sysplugins/smarty_resource_recompiled.php',
		  'smarty_resource_uncompiled' => 'lib/smarty/libs/sysplugins/smarty_resource_uncompiled.php',
		  'smarty_security' => 'lib/smarty/libs/sysplugins/smarty_security.php',
		);

	    // Cette fonction permet de lister toutes les classes du répertoire models
	   	$modelsDirectory = dirname(__FILE__).'/models';
	   	if($handle = opendir($modelsDirectory)){
	   		while(false !== ($entry = readdir($handle))) {
	   			if(strpos($entry, '.class.php')) {
	   				$classes[strtolower(str_replace('.class.php','',$entry))] = 'lib/models/' . $entry;
	   			}
	   		}
	   	}

        // On inclue la classe en s'aidant du tableau
if(empty($classes[strtolower($class)])) {var_dump($class);die();}
        require_once dirname(__FILE__).'/../'.$classes[strtolower($class)];
    }

    // N'oublions pas d'ajouter notre fonction à la pile d'autoload
    spl_autoload_register('autoload');
?>
