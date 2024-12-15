@extends('layouts.app')

@section('head')

<link href="{{ asset('css/static.css') }}" rel="stylesheet">

@endsection

@section('content')

<div class="row">
  <h2>FAQ & Help</h2>
</div>
<div class="row">
  <p>We summarized frequently asked questions below. If those do not help with your issues, please <b><a href="{{  route('static.contact')  }}">contact us</a></b> with your problem.</p>
</div>

<button class="accordion">How do I create an account?</button>
<div class="panel">
  <p>To create an account, click on the Login button on the homepage, then go to the registration and fill the form with your username, name, email, and password.
</p>
</div>

<button class="accordion">How can I reset my password?</button>
<div class="panel">
  <p>If you have forgotten your password, click Forgot Password? link on the login page. Enter your registered email, and you will receive an email with a new password.</p>
</div>

<button class="accordion">How do I change my password?</button>
<div class="panel">
  <p>If you want to change your password, login and go to the settings section. There, you can change your password and your page privacy.</p>
</div>

<button class="accordion">How do I share a post about my pet?</button>
<div class="panel">
  <p>To share a post, first login to your account. Then, go to the Create Post section. Insert a caption and upload a picture or video. You can upload jpg, jpeg or png file formats. Then, click the button to create your post.</p>
</div>

<button class="accordion">Can I edit or delete a post I have shared?</button>
<div class="panel">
  <p>Yes, you can edit or delete your posts. Navigate to the post you wish to change, TBD.</p>
</div>

<button class="accordion">How do I join or create a group?</button>
<div class="panel">
  <p>To join a group, press the Search Groups button on the group section. Look for a group that you want to join and press the Join button.<br><br>
To create a group, click on the Create Group button in the group section. Then, enter the group name, its description and privacy settings and press the Create Group button.
</p>
</div>


<button class="accordion">Who can see my posts?</button>
<div class="panel">
  <p>The visibility of your posts depends on your privacy settings. If your profile is public, everyone can see your posts. But if your profile is private, only your followers can see and interact with your posts.</p>
</div>

<button class="accordion">What should I do if someone is being rude or offensive?</button>
<div class="panel">
  <p>Go to the contact page and send us a message. You should give us the username and the reason for reporting this person. We will then process your request as soon as possible and ban them if</p>
</div>

<button class="accordion">How do I customize my profile?</button>
<div class="panel">
  <p>To customize your profile, go to your profile by clicking your username or profile picture. Click on Edit Profile and update your profile picture, cover photo, bio, and pet details. Don't forget to save your changes.</p>
</div>

<button class="accordion">How can I delete my account?</button>
<div class="panel">
  <p>If you wish to delete your account, navigate to Settings in the menu and click on Delete Account. This will permanently remove your profile and your data from PawPals. Your comments and likes will be anonymized.</p>
</div>

</div>

@endsection

@section('scripts')
<script>
  var acc = document.getElementsByClassName("accordion");
  var i;

  for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
      // Close all other accordion items
      for (var j = 0; j < acc.length; j++) {
        if (acc[j] !== this && acc[j].classList.contains("active")) {
          acc[j].classList.remove("active");
          var openPanel = acc[j].nextElementSibling;
          openPanel.style.maxHeight = null; // Close the panel
        }
      }

      // Toggle the current accordion item
      this.classList.toggle("active");
      var panel = this.nextElementSibling;
      if (panel.style.maxHeight) {
        panel.style.maxHeight = null;
      } else {
        panel.style.maxHeight = panel.scrollHeight + "px";
      }
    });
  }

</script>

@endsection