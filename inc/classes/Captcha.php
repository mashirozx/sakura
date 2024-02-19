<?php
if (akina_option('verification_type') == 'CF Turnstile') {
     $robot_comments = '<label class="siren-checkbox-label"> <div class="cf-turnstile" data-sitekey="'.akina_option('site_key').'"></div> </label>';}               
     elseif (akina_option('verification_type') == 'Google reCAPTCHA' && akina_option('rehidden') == '0') {
     $robot_comments = '<label class="siren-checkbox-label"><div class="g-recaptcha" data-sitekey="'.akina_option('site_key').'"></div></label>';}                  
     elseif(akina_option('verification_type') == 'Google reCAPTCHA v3'){
     $robot_comments = '<label class="siren-checkbox-label"><script>grecaptcha.ready(function() { grecaptcha.execute("'.akina_option('site_key').'", {action: "submit"}).then(function(token) { var form = document.getElementById("commentform"); var input = document.createElement("input"); input.setAttribute("type", "hidden"); input.setAttribute("name", "g-recaptcha-response"); input.setAttribute("value", token); form.appendChild(input); }); });</script></label>';}
     elseif(akina_option('verification_type') == 'mCAPTCHA'){
     $robot_comments = '<label class="siren-checkbox-label"><div id="mcaptcha__widget-container"></div><script charset="utf-8">let config = {widgetLink: new URL("'.akina_option('site_key').'"),};new mcaptchaGlue.default(config);</script></label>';} 
     elseif(akina_option('verification_type') == 'Theme CAPTCHA'){ 
     $robot_comments = '<label class="siren-checkbox-label"><input class="siren-checkbox-radio" type="checkbox" name="no-robot"><span class="siren-no-robot-checkbox siren-checkbox-radioInput"></span>'.__('I\'m not a robot', 'sakura').'</label>';}?>
