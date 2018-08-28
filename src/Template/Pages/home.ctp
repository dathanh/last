<script src="http://code.responsivevoice.org/responsivevoice.js"></script>
<div style="display: none;" id="content" chapter="<?= $chapter ?>" ><?= $content ?></div>
<input id="btn-submit" type="submit" name="btn" value="Gửi"/>
<script>
    $(document).ready(() => {
        function voiceStartCallback() {
            console.log("Voice started");
        }

        function voiceEndCallback() {
            $.ajax({
                url: '/processGetContent',
                dataType: 'json',
                type: 'POST',
                data: {
                    chapter: parseInt($('#content').attr('chapter')) + 1,
                },
                cache: false,
                success: function (response) {
                    $('#content').attr('chapter', response.data);
                    $('#content').text('');
                    responsiveVoice.speak(response.message, 'Vietnamese Female', parameters);
                },

            });
            console.log("Voice ended");
        }

        var parameters = {
            onstart: voiceStartCallback,
            onend: voiceEndCallback
        }
        $('#btn-submit').on('click', () => {
            responsiveVoice.speak($('#content').text(), 'Vietnamese Female', parameters);

        });
    });

</script>