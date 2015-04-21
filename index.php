<html>
    <head>
        <title>Wikipedia API</title>
        <link href="custom.css" type="text/css" rel="stylesheet" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script type="text/javascript">
            function autocomplet() {
                var strval = $("#searchTerm").val();
                if(strval!=""){
                    var data = $('#form_id').serialize();
                    $("#search_data_result").empty();
                    $("#popup").show();
                    $.ajax({
                        url: "action_table.php",
                        dataType:'json',
                        data: data,
                        type: "POST",
                        success: function(response){ 
                            $("#popup").hide(); 
                            console.log(response);
                            $('#search_data_result').empty();
                            for(var k=0;k<response.data.length;k++){
                                var str = response.data[k].charityName;
                                //alert(str);
                                var link_data = '<a href="javascript:void(0);" class="pop" id="id_linked">'+response.data[k].charityName+'</a>';   
                                //alert(link_data);                         
                                $('#search_data_result').append(link_data+"<br>");
                                
                            }    
                        },                    
                    });                   
                }
            }    
            $(document).ready(function () {
                $("#search_data_result").on('click', '.pop' , function () {
                    $("#popup").show();
                    $("#table_chrity").empty();
                    str = this.text;
                    $.ajax({
                       url: "get_result.php",
                        data: {"str":str},
                        type: "POST",
                        success: function(response){ 
                        	//alert(response);
                            //console.log(response);
                            $("#popup").hide();
                            $("#search_data_result").empty();
                            str = response.split("<br>,");
                            var charityname = str[0];
                            var city = str[1];
                            var state = str[2];
                            var zip = str[3];
                            var desc = str[4];
                            var html_response= "<tr><td>Charity Name:</td><td>"+charityname+"</td></tr><tr><td>Description:</td><td>"+desc+"</td></tr><tr><td>City:</td><td>"+city+"</td></tr><tr><td>State:</td><td>"+state+"</td></tr><tr><td>Zip Code:</td><td>"+zip+"</td></tr>";
                            $("#table_chrity").append(html_response);
                            $("#searchTerm").val("");
                            
                        },    
                    });                    
                });
            });    

        </script>
    </head>    
    <body>
        <div style="display:none;" id="popup" class="progress progress-striped active progress-sm">
            <div id="progress" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"><img style="margin-top:-112px; margin-left:100px;" src="image.gif"></div>
        </div>
        <form method="post" action="" id="form_id">
            Charity Name: <input type="text" name="searchTerm" id="searchTerm" >
            <input type="hidden" name="user_key" value="24e4df8f59b250ce18489502f63221b8" />
            <input class="submitbtn1" type="button" name="submit" value="Search" onclick="return autocomplet();"/>
        </form>
        <table id="table_chrity" cellspacing="5" cellpadding="5" border="0"></table>
        <div id="search_data_result"></div>
        <div id="search_data"></div>
    </body>
</html>