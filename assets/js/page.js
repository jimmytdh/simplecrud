$(function(){
    $('.nav-link').click(function(event){
        event.preventDefault();
        let page = $(this).attr('href').substring(1);

        if(page === 'dashboard')
            loadDashboard();
        else if(page ==='profile')
            loadProfile();
        else if(page ==='about')
            $('#main').load('pages/about.html');
        else if(page ==='jimmy'){
            $('#main').load('members/jimmytdh.html',function(){

            });
        }
    });
});