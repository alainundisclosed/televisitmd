<?php 
    date_default_timezone_set('America/New_York');
    
    include 'header.php';
    
    // states
    $states = $db->get('states');
?>
<style>
    .ourteam-photo:hover .flip-card{
        transform: rotateY(180deg);
    }
    
    .checkbox-menu li label {
        display: block;
        padding: 3px 10px;
        clear: both;
        font-weight: normal;
        line-height: 1.42857143;
        color: #333;
        white-space: nowrap;
        margin:0;
        transition: background-color .4s ease;
    }
    .checkbox-menu li input {
        margin: 0px 5px;
        top: 2px;
        position: relative;
    }
    
    .checkbox-menu li.active label {
        background-color: #cbcbff;
        font-weight:bold;
    }
    
    .checkbox-menu li label:hover,
    .checkbox-menu li label:focus {
        background-color: #f5f5f5;
    }
    
    .checkbox-menu li.active label:hover,
    .checkbox-menu li.active label:focus {
        background-color: #b8b8ff;
    }
    .pr-15px {
        padding-right: 15px;
    }
    .pl-15px {
        padding-left: 15px;
    }
    
    #stateList {
        max-height: 300px;
        overflow-y: auto;
    }
    
    .pt-50px {
        padding-top: 50px;
    }
</style>
<div class="container">
	<div class="col-lg-12 col-md-12 col-sm-12 pt-50px">
        <div class="container">
            <div class="col-lg-12 col-md-12 col-sm-12" id="search_widget">
                <div class="col-md-6 col-sm-8 col-lg-5 col-xs-12">
                    <span>
                      <label class="pr-15px">
                        <input type="checkbox" name="checkboxDom" id="checkboxDom"> Dom
                      </label>
                      <!--<label>-->
                      <!--  <input type="checkbox" id="onlineStatus"> Online Now-->
                      <!--</label>-->
                    </span>
                    
                    <span>
                        <span class="pl-15px"><b>States: </b>&nbsp;</span>
                        <span class="dropdown">
                          <button class="btn btn-default dropdown-toggle" type="button" 
                                  id="dropdownMenu1" data-toggle="dropdown" 
                                  aria-haspopup="true" aria-expanded="true">
                            <i class="glyphicon glyphicon-search"></i>
                            <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu checkbox-menu allow-focus" id="stateList" aria-labelledby="dropdownMenu1">
                            <?php 
                                foreach($states as $state):
                            ?>
                                <li >
                                  <label>
                                    <input type="checkbox" name="searchStates" value="<?=$state['state']?>"> <?=$state['state']?>
                                  </label>
                                </li>
                            <?php endforeach; ?>
                          </ul>
                        </span>
                    </span>
                    
                </div>
                <div class="col-md-3 col-sm-4 col-lg-3 col-xs-12">
                    <span class="pl-15px"><button class="btn btn-primary" id="providerSearch">Search</button></span>
                    <span class="pl-15px"><button class="btn btn-warning" id="resetSearch">Reset</button></span>
                </div>
            </div>
            
            <div id="doctorCards"></div>
	    </div>
	    <div class="split-bar"></div>
	</div>
<?php include 'footer.php';?>
<script>
    $(".checkbox-menu").on("change", "input[type='checkbox']", function() {
       $(this).closest("li").toggleClass("active", this.checked);
    });
    
    $(document).on('click', '.allow-focus', function (e) {
      e.stopPropagation();
    });
    
    getProvidersList('all', 'all', 'no');
    
    function getProvidersList(domOrAll, search_states, onlineOnly) {
        $.ajax({
           url: 'ajaxGetProviders.php',
           type: 'POST',
           data: {getProviders: 'getProviders', domOrAll, search_states, onlineOnly, showOnlineGreenLight: 'no', showPddNumber: 'no'},
           success: function(res) {
               $('#doctorCards').html(res);
           }
        });
        
    }
    
    $('#providerSearch').click(function() {
        
        var domOrAll = $('#checkboxDom').is(':checked') ? 'dom' : 'all';
        // var onlineOnly = $('#onlineStatus').is(':checked') ? 'yes' : 'no';
        var onlineOnly = 'no';
        var states = [];
        $.each($("input[name='searchStates']:checked"), function(){
          states.push($(this).val());
        });
        var search_states = states.join(", ");
        if(!search_states) {
            search_states = 'all';
        }
        
        getProvidersList(domOrAll, search_states, onlineOnly);
    });
    
    $('#resetSearch').click(function() {
        $('#search_widget input:checkbox').prop('checked', false);
        $('#stateList li').removeClass('active');
        $('input[type="radio"][value="all"]').not(':checked').prop("checked", true);
        getProvidersList('all', 'all', 'no');
    });
    
</script>