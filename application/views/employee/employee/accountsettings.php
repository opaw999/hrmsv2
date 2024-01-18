  <div class="am-pagebody">  
    <div class="card pd-20 pd-sm-30 ">
      <h6 class="card-body-title"><?= $title;?></h6>
      <br>
      <div class="row" data-widget="tab-slider">
        <div class="col-3">
          <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <a onclick="nav('username','employee/change_user')" class="nav-link active" id="username" href="#">Change Username</a>
            <a  onclick="nav('password','employee/change_password')" class="nav-link" id="password"  href="#">Change Password</a>
            <a  onclick="nav('phone','employee/change_phone')" class="nav-link" id="phone" href="#">Update Phone Numbers</a>
          </div>
        </div> <!-- col-3 -->
        <div class="col-9">
          <div class="tab-content" id="nav-content">
          </div>
     </div>
    </div>
  </div> 
</div>

