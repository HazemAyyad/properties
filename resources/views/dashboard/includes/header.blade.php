<!DOCTYPE html>

<html
    lang="en"
    class="light-style layout-navbar-fixed layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="{{asset('')}}/assets/"
    data-template="vertical-menu-template-starter"
>
<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>{{config('app.name')}} | </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{asset('/')}}assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet"
    />

    <!-- Icons -->
    <link rel="stylesheet" href="{{asset('/assets/vendor/fonts/fontawesome.css')}}" />
    <link rel="stylesheet" href="{{asset('/assets/vendor/fonts/tabler-icons.css')}}" />
    <link rel="stylesheet" href="{{asset('/assets/vendor/fonts/flag-icons.css')}}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{asset('/assets/vendor/css/rtl/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{asset('/assets/vendor/css/rtl/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{asset('/assets/css/demo.css')}}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{asset('/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
    <link rel="stylesheet" href="{{asset('/assets/vendor/libs/node-waves/node-waves.css')}}" />
    <link rel="stylesheet" href="{{asset('/assets/vendor/libs/toastr/toastr.css')}}" />
    <link rel="stylesheet" href="{{asset('/assets/vendor/libs/animate-css/animate.css')}}" />
    <link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css
" rel="stylesheet"></link>
    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{asset('/assets/vendor/js/helpers.js')}}"></script>
@yield('style')
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
{{--    <script src="{{asset('/assets/vendor/js/template-customizer.js')}}"></script>--}}
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{asset('/assets/js/config.js')}}"></script>
    <style>

        #ul_notifications {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .notification-drop {
            font-family: 'Ubuntu', sans-serif;
            color: #444;
        }

        .notification-drop .item {
            padding-right: 15px;
            font-size: 18px;
            position: relative;
            border-bottom: 1px solid #ddd;
        }

        .notification-drop .item:hover {
            cursor: pointer;
        }

        .notification-drop .item i {
            margin-left: 10px;
        }

        .notification-drop .item ul li {
            font-size: 16px;
            padding: 10px 10px 10px 10px;
        }

        .notification-drop .item ul li:hover {
            background: #ddd;
            color: rgba(0, 0, 0, 0.8);
        }

        @media screen and (min-width: 500px) {
            .notification-drop {
                display: flex;
                justify-content: flex-end;
            }

            .notification-drop .item {
                border: none;
            }
        }



        .notification-bell {
            font-size: 20px;
        }

        .btn__badge {
            background: #ff0000;
            color: white;
            font-size: 10px;
            position: absolute;
            top: -3px;
            right: 0px;
            padding: 0px 4px 0px 4px;
            border-radius: 50%;
            height: 10px;
            padding-bottom: 23px;
            font-weight: bolder;
        }

        .pulse-button {
            box-shadow: 0 0 0 0 rgba(0, 0, 255, 0.5);
            -webkit-animation: pulse 1.5s infinite;
        }

        .pulse-button:hover {
            -webkit-animation: none;
        }

        @-webkit-keyframes pulse {
            0% {
                -moz-transform: scale(0.9);
                -ms-transform: scale(0.9);
                -webkit-transform: scale(0.9);
                transform: scale(0.9);
            }

            70% {
                -moz-transform: scale(1);
                -ms-transform: scale(1);
                -webkit-transform: scale(1);
                transform: scale(1);
                box-shadow: 0 0 0 50px rgba(255, 0, 0, 0);
            }

            100% {
                -moz-transform: scale(0.9);
                -ms-transform: scale(0.9);
                -webkit-transform: scale(0.9);
                transform: scale(0.9);
                box-shadow: 0 0 0 0 rgba(255, 0, 0, 0);
            }
        }

        .notification-text {
            font-size: 14px;
            font-weight: bold;
        }

        .notification-text span {
            float: right;
        }
    </style>
    <script>
        const userId = "{{Auth::id()}}";
    </script>
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <!-- Font Awesome CSS -->
    <!-- jQuery -->
    <!-- Toastr JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <!-- Pusher JavaScript -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <style>
        /* Customize the toast appearance */
        .toast-success.custom-toast {
            background-color: white !important; /* White background */
            color: #28a745 !important; /* Green text color */
            border: 1px solid #28a745; /* Green border */
        }

        /* Customize the progress bar color to green */
        .toast-success.custom-toast .toast-progress {
            background-color: #28a745 !important;
        }

        /* Style the close button inside a red circle */
        .toast-close-button {
            background-color: red !important;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: bold;
        }

        .toast-close-button:hover {
            background-color: darkred;
        }

        /* Modify the layout of the toast message */
        .toast-title {
            font-weight: bold;
        }

        .toast-message {
            font-size: 14px;
        }

        /* Customize the toast container */
        #toast-container {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 9999;
        }

        /* Optional: Add a fade-in effect */
        .toast {
            opacity: 0;
            animation: fadeIn 0.5s forwards;
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        .notification-content i,.notification-content span{
            color: #0a3622;
        }
    </style>

    <script>
        Pusher.logToConsole = true;

        // Initialize Pusher
        var pusher = new Pusher('d3259213b97686091820', {
            cluster: 'ap2'
        });

        // Subscribe to the channel
        var channel = pusher.subscribe('notification');

        // Define the notification audio
        const audio = new Audio('{{ asset('audio/notfiy.mp3') }}');
        audio.preload = 'auto'; // Preload audio for quicker playback
        audio.loop = false;     // Ensure it doesn't loop by default
        let audioUnlocked = false; // Flag to track if the audio is unlocked

        // Function to unlock audio
        const unlockAudio = () => {
            if (!audioUnlocked) {
                console.log('User interaction detected - unlocking audio.');
                audio.muted = true; // Mute audio for autoplay unlock
                audio.play()
                    .then(() => {
                        audioUnlocked = true;
                        console.log('Audio unlocked successfully.');
                        audio.pause();
                        audio.muted = false; // Unmute for actual playback
                        audio.currentTime = 0; // Reset playback position
                    })
                    .catch(error => console.error('Audio unlock failed:', error));
            }
        };

        // Function to play the audio
        const playAudio = () => {
            if (audioUnlocked) {
                console.log('Attempting to play audio...');
                audio.play()
                    .then(() => console.log('Audio played successfully.'))
                    .catch(error => console.error('Audio playback failed:', error));
            } else {
                console.log('Audio is locked, cannot play.');
            }
        };

        // Add event listeners to unlock audio on user interaction
        document.addEventListener('click', unlockAudio, { once: true });
        document.addEventListener('keydown', unlockAudio, { once: true });

        // Listen to the Pusher notification event
        channel.bind('notification.event', function (data) {
            console.log('Received data:', data); // Log the received data

            if (data.author && data.title) {
                // Generate notification HTML
                var notificationsWrapper = $('#ul_notifications');
                var notificationsCountElem = $('#notifications-item-count');
                var notificationsCount = parseInt(notificationsCountElem.data('count')) || 0;
                var existingNotifications = notificationsWrapper.html();
                var newNotificationHtml = `
                <li class="list-group-item list-group-item-action dropdown-notifications-item">
                    <a class="d-flex" href="{{url('/admin/notification/show')}}/${data.id}">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar">
                                <img src="{{ asset('bell.png') }}" alt class="h-auto rounded-circle"/>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${data.title}</h6>
                            <small class="text-muted">${data.time}</small>
                        </div>
                        <div class="flex-shrink-0 dropdown-notifications-actions">
                            <span class="dropdown-notifications-read">
                                <span class="badge badge-dot"></span>
                            </span>
                        </div>
                    </a>
                </li>`;

                notificationsWrapper.html(newNotificationHtml + existingNotifications);
                notificationsCount += 1;
                notificationsCountElem.data('count', notificationsCount);
                notificationsCountElem.text(notificationsCount);
                notificationsWrapper.show();

                // Show Toastr notification
                toastr.success(
                    `<a target="_blank" href="${data.url}">
                    <div class="notification-content">
                        <i class="fas fa-check-circle" ></i> <!-- Check Icon -->
                        <span>${data.author}</span>
                        <i class="fas fa-book" style="margin-left: 20px;"></i>
                        <span>${data.title}</span>
                    </div>
                </a>`,
                    __('New Aqar '),
                    {
                        closeButton: true,          // Show close button
                        progressBar: true,          // Enable progress bar
                        timeOut: 5000,              // Duration in milliseconds
                        extendedTimeOut: 1000,      // Extra time when hovered
                        positionClass: "toast-top-right", // Position on screen
                        escapeHtml: false,          // Allow HTML in the message content
                        toastClass: "toast-success custom-toast", // Custom class
                    }
                );

                // Play audio if it's unlocked
                playAudio();
            } else {
                console.error('Invalid data received:', data);
            }
        });

        // Debugging line to confirm Pusher connection
        pusher.connection.bind('connected', function () {
            console.log('Pusher connected');
        });
    </script>










</head>

<body>

<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->
