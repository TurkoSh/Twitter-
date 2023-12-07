document.addEventListener('DOMContentLoaded', function() {
    // Handle reply button clicks
    document.querySelectorAll('.reply-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        const tweetId = this.getAttribute('id').split('-')[2];
        document.getElementById(`reply-form-${tweetId}`).classList.toggle('d-none');
      });
    });
  
    // Handle reply form submissions
    document.querySelectorAll('.reply-form').forEach(function(form) {
      form.addEventListener('submit', async function(event) {
        event.preventDefault();
  
        const formData = new FormData(this);
        try {
          const response = await fetch('./reply.php', {
            method: 'POST',
            body: formData,
          });
  
          if (response.ok) {
            alert('Reply sent!');
          } else {
            alert('Failed to reply');
          }
        } catch (error) {
          alert('Failed to reply');
        }
  
        this.classList.add('d-none');
        this.reset();
      });
    });
  
    // Handle retweet button clicks
    document.querySelectorAll('.retweet-btn').forEach(function(btn) {
      btn.addEventListener('click', async function() {
        const tweetId = this.getAttribute('id').split('-')[2];
        const formData = new FormData();
        formData.append('tweet_id', tweetId);
  
        try {
          const response = await fetch('./retweet.php', {
            method: 'POST',
            body: formData,
          });
  
          if (response.ok) {
            alert('Retweeted successfully!');
          } else {
            alert('Failed to retweet');
          }
        } catch (error) {
          alert('Failed to retweet');
        }
      });
    });
  });
  