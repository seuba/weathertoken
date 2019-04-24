define([
    'js/postmonger'
], function(
    Postmonger
) {
    'use strict';

    var connection = new Postmonger.Session();
    var payload = {};
   
    var steps = [ 
        { "label": "Step 1", "key": "step1" },
		{ "label": "Step 2", "key": "step2" },
		{ "label": "Step 3", "key": "step3" }
    ];
    var currentStep = steps[0].key;

    $(window).ready(onRender);

    connection.on('initActivity', initialize);
    connection.on('requestedTokens', onGetTokens);
    connection.on('requestedEndpoints', onGetEndpoints);
	connection.on('clickedNext', onClickedNext);
    connection.on('clickedBack', onClickedBack);
    connection.on('gotoStep', onGotoStep);

    function onRender() {
        connection.trigger('ready');
		connection.trigger('requestTokens');
        connection.trigger('requestEndpoints');
		
        $('#select1').change(function() {
            var message = getMessage();
            connection.trigger('updateButton', { button: 'next', enabled: Boolean(message) });

            $('#message').html(message);
        });
	     $('#tokenbutton').click(function() {
            var message2 = getMessage2();
            connection.trigger('updateButton', { button: 'next', enabled: Boolean(message) });

            $('#message2').html(message2);
        });
	}
	function initialize (data) {
        if (data) {
            payload = data;
        }

        var message; 
		var message2;
        var hasInArguments = Boolean(
            payload['arguments'] &&
            payload['arguments'].execute &&
            payload['arguments'].execute.inArguments &&
            payload['arguments'].execute.inArguments.length > 0
        );

        var inArguments = hasInArguments ? payload['arguments'].execute.inArguments : {};

        $.each(inArguments, function(index, inArgument) {
            $.each(inArgument, function(key, val) {
                if (key === 'message') {
                    message = val;
                };
				if (key === 'message2') {
                    message2 = val;
                }
            });
        });

        if (!message) {
            showStep(null, 1);
            connection.trigger('updateButton', { button: 'next', enabled: false });
        }
		 
		else {
            $('#select1').find('option[value='+ message +']').attr('selected', 'selected');
            $('#message').html(message);
			$('#token').val();
			$('#message2').html(message2);
            showStep(null, 3);
        }
    }

    function onGetTokens (tokens) {
         console.log(tokens);
    }

    function onGetEndpoints (endpoints) {
    }

    function onClickedNext () {
        if (
            (currentStep.key === 'step3')
        ) {
            save();
        } else {
            connection.trigger('nextStep');
        }
    }

    function onClickedBack () {
        connection.trigger('prevStep');
    }

    function onGotoStep (step) {
        showStep(step);
        connection.trigger('ready');
    }

    function showStep(step, stepIndex) {
        if (stepIndex && !step) {
            step = steps[stepIndex-1];
        }

        currentStep = step;

        $('.step').hide();

        switch(currentStep.key) {
            case 'step1':
                $('#step1').show();
                connection.trigger('updateButton', {
                    button: 'next',
                    enabled: Boolean(getMessage())
                });
                connection.trigger('updateButton', {
                    button: 'back',
                    visible: false
                });
                break;
            case 'step2':
                $('#step2').show();
                connection.trigger('updateButton', {
                    button: 'back',
                    visible: true
                });
                connection.trigger('updateButton', {
                    button: 'next',
                    text: 'next',
                    visible: true,
					 enabled: Boolean(getMessage2())
                });
			 case 'step3':
                $('#step3').show();
                connection.trigger('updateButton', {
                    button: 'back',
                    visible: true
                });
                connection.trigger('updateButton', {
                    button: 'next',
                    text: 'next',
                    visible: true
					 
                });
                break;
        }
    }

    function save() {
        var name = $('#select1').find('option:selected').html();
        var value = getMessage();
		 var name2 = $('#token').val().html();
        var value2 = getMessage2();

        payload.name = name;
		payload.name2 = name2;
		payload['arguments'].execute.inArguments = [{ "message": value }, { "message2": value2 }];
		payload['metaData'].isConfigured = true;
		connection.trigger('updateActivity', payload);
    }

    function getMessage() {
		var albert = $('#select1').find('option:selected').attr('value').trim();
		console.log (albert);
        return $('#select1').find('option:selected').attr('value').trim();
    }
	 function getMessage2() {
		var albert2 = $('#token').val().trim();
		console.log (albert2);
        return $('#token').val().trim();
    }

});
