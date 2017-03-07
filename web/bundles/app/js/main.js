require.config({
    'baseUrl' : window.baseUrl,
    'paths' : {
    	'jquery' : '../../lib/jquery/jquery-2.2.3.min',
    	'bootstrap' : '../../lib/bootstrap/js/bootstrap.min',
    	'alertify' : '../../lib/alertifyjs/alertify',
    	'slimscroll' : '../../lib/slimscroll/jquery.slimscroll.min',
    	'fastclick' : '../../lib/fastclick/fastclick.min',
    	'icheck' : '../../lib/icheck/icheck.min',
    	'app' : '../app',
    	'demo' : '../demo' /// fix it, here is some problems that break layout page
    },
    'shim' : {
    	'bootstrap' : {
    		'deps' : ['jquery']
    	},
    	'slimscroll' : {
    		'deps' : ['jquery']
    	},
    	'app' : {
    		'deps' : ['bootstrap']
    	},
    	'demo' : {
    		'deps' : ['jquery','app']
    	}
    }
});

define('main', ['jquery', 'bootstrap', 'alertify', 'slimscroll', 'fastclick', 'icheck', 'app', 'demo'], function($){

	//make it for auth only

	/*$(function () {
	    $('input').iCheck({
	      checkboxClass: 'icheckbox_square-blue',
	      radioClass: 'iradio_square-blue',
	      increaseArea: '20%' // optional
	    });
	  });*/


	var initialized = false,
		components = [],
		//maybe move it to separate component
		componentsCongiguration = {
		'global' : {
			'component1' : {}
		},
		'App' : {
			//'components' : {},
			//'controllers' : {}
			'Default' : {
				//'components' : {},
				//'actions' : {}
				'index' : {
					'component2' : {},
					'component3' : {}
				}
			}
		}
	};

	var init = function(){
		if (initialized) {
			return false;
		}

		for (component in componentsCongiguration['global']) {
			components.push(component);
		}

		//check page context here and declare local variable for current component list

		if (typeof componentsCongiguration[pageContext['bundle']][pageContext['controller']][pageContext['action']] !== 'undefined') {
			for (component in componentsCongiguration[pageContext['bundle']][pageContext['controller']][pageContext['action']]) {
				components.push(component);
			}
		}

		console.log(components);

		require(['require'].concat(components), function(require){
			for (index in components) {
				require(components[index]).init();
			}
		});

		initialized = true;

	};

	return {
		'init' : init
	};
});

require(['main'], function(main){
	main.init();
});