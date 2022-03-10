@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <center>
                <button id="btn-nft-enable" onclick="initFirebaseMessagingRegistration()" class="btn btn-danger btn-xs btn-flat">Allow for Notification</button>
            </center>
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ url('send-notification') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" name="title" id="title" required>
                        </div>
                        <div class="form-group">
                            <label>Body</label>
                            <textarea class="form-control" name="body" id="body" required></textarea>
                          </div>
                        <button type="button" class="btn btn-primary btn-sendNotif">Send Notification</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js"></script>
<script>
    var firebaseConfig = {
        apiKey: "AIzaSyDz2XnmikLRO8-OtKDtyamKtXw9siYoX4g",
        authDomain: "learnapi-2f9e0.firebaseapp.com",
        databaseURL: "https://learnapi-2f9e0-default-rtdb.firebaseio.com",
        projectId: "learnapi-2f9e0",
        storageBucket: "learnapi-2f9e0.appspot.com",
        messagingSenderId: "43647748479",
        appId: "1:43647748479:web:65b36ef89d8d5b80a7724a",
        measurementId: "G-194896LJQN"
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

// Get registration token. Initially this makes a network call, once retrieved
// subsequent calls to getToken will return from cache.
messaging.getToken({ vapidKey: 'BGGTXEzNWPBcpRZJcmHFM2VdN4JBRQqiLl963jUsNgvmGfcTVzljn_zEAr2fbk1KS7xokw98s7PBi3Q2ihHOl0E' }).then((currentToken) => {
  if (currentToken) {
    // Send the token to your server and update the UI if necessary
    // ...
    console.log('Registration token available. Token is '+currentToken);
  } else {
    // Show permission request UI
    // ...
    console.log('No registration token available. Request permission to generate one.');
  }
}).catch((err) => {
  console.log('An error occurred while retrieving token. ', err);
  // ...
});

    function initFirebaseMessagingRegistration() {
            messaging
            .requestPermission()
            .then(function () {
                return messaging.getToken()
            })
            .then(function(token) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '{{ route("save-token") }}',
                    type: 'POST',
                    data: {
                        token: token
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        alert('Token saved successfully.');
                    },
                    error: function (err) {
                        console.log('User Chat Token Error'+ err);
                    },
                });

            }).catch(function (err) {
                toastr.error('User Chat Token Error'+ err, null, {timeOut: 3000, positionClass: "toast-bottom-right"});
            });
     }  
    

    // messaging.onMessage(function(payload) {
    //     const noteTitle = payload.notification.title;
    //     const noteOptions = {
    //         body: payload.notification.body,
    //         icon: payload.notification.icon,
    //     };
    //     new Notification(noteTitle, noteOptions);
    // });

    $(document).on("click",".btn-sendNotif",function() {
        if ($('#title').val()=='' || $('#body').val()=='') {
            alert('Please Input Form');
            return false;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: $(this).closest('form').attr('action'),
            type: $(this).closest('form').attr('method'),
            data: {
                title: $('#title').val(),
                body: $('#body').val(),
            },
            dataType: 'json',
            success: function (response) {
                $('form').trigger('reset');
                alert('Success Send Notif');
            },
            error: function (err) {
                console.log(err);
            },
        });
    });
</script>
@endsection
