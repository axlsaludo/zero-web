$(document).ready(function() {
    // Function to load leaderboard data
    function loadLeaderboard() {
        $.ajax({
            url: '../db/leaderboard.php',  // URL to your PHP script that fetches leaderboard data
            type: 'GET',  // or 'POST' depending on your server-side implementation
            dataType: 'json',  // Assuming your server returns JSON data
            success: function(data) {
                // Clear existing leaderboard content
                $('#leaderboards').empty();

                // Add header
                $('#leaderboards').append('<h3>Leaderboards</h3>');

                // Add each leaderboard entry dynamically
                $.each(data, function(index, row) {
                    var rank = index + 1;
                    var fullName = row.first_name + ' ' + row.last_name;
                    var score = row.score;
                    $('#leaderboards').append('<p>' + rank + ' | ' + fullName + ' - ' + score + '</p>');
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading leaderboard:', error);
                $('#leaderboards').html('<p>Error loading leaderboard.</p>');
            }
        });
    }

    // Call the function initially
    loadLeaderboard();

    // Optionally, refresh leaderboard periodically
    setInterval(loadLeaderboard, 60000);  // Refresh every 1 minute (adjust as needed)
});
