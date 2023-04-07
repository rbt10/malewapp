$(document).ready(function (){
    resetStarColors();
    $('.fa-star').mouseover(function (){
        console.log("hello")
    });
    $('.fa-star').mouseleave(function (){
        console.log("no")
    })

    function resetStarColors() {
        $('.fa-star').css('color', 'white');
    }
})