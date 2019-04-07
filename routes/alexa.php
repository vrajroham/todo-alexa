<?php

AlexaRoute::launch('/alexa-endpoint', 'App\Http\Controllers\AlexaController@open');
AlexaRoute::intent('/alexa-endpoint', 'TotalTasks', 'App\Http\Controllers\AlexaController@totalTasks');
