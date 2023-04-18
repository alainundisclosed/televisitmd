<!DOCTYPE html>
<html>
<head>
    <title>TelevisitMD Video Call Room</title>
 
</head>
<body>
    <h1>TelevisitMD Video Call Room</h1>
    <div id="root"></div>

    <!-- Release the JavaScripts -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="//media.twiliocdn.com/sdk/js/video/releases/2.7.1/twilio-video.min.js"></script>
    <!--<script src="./twilio-video.js"></script>-->
    <script>

    // const Video = require('twilio-video');
    const Video = Twilio.Video;
    const root = document.getElementById('root');
    // $('#start').on('click', function() {
  

        $.ajax({
        url: 'twilioserver.php',
        type: 'GET',
        data: {name: $('#my-name').val()},
        dataType: 'json',
        success: function(data) {
            console.log('Token response:');
            console.log(data);


            Video.connect( data.token, { name: '5rd room' }).then(room => {
                console.log('Connected to Room "%s"', room.name);
                
                Video.createLocalVideoTrack().then(track => {
                    root.appendChild(track.attach());
                });
                
                //console.log( room.participants );
                
                room.participants.forEach(participantConnected);
                room.on('participantConnected', participantConnected);

                room.on('participantDisconnected', participantDisconnected);
                room.once('disconnected', error => room.participants.forEach(participantDisconnected));
                
            });
           
        },
        error: function(e) { 
            console.log(e.message);
        }
        });


        function participantConnected(participant) {
            console.log('Participant "%s" connected', participant.identity);
    
            const div = document.createElement('div');
            div.id = participant.sid;
            div.innerText = participant.identity;
    
            participant.on('trackSubscribed', track => trackSubscribed(div, track));
            participant.on('trackUnsubscribed', trackUnsubscribed);
    
            participant.tracks.forEach(publication => {
                if (publication.isSubscribed) {
                trackSubscribed(div, publication.track);
                }
            });
    
            document.body.appendChild(div);
        }

        function participantDisconnected(participant) {
            console.log('Participant "%s" disconnected', participant.identity);
            document.getElementById(participant.sid).remove();
        }

        function trackSubscribed(div, track) {
            div.appendChild(track.attach());
        }

        function trackUnsubscribed(track) {
            track.detach().forEach(element => element.remove());
        }


    //});
 
    </script>
</body>
</html>