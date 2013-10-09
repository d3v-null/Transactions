// http://www.building58.com/examples/tabSlideOut.html
// slide out tab for transaction history
$(function(){
$('.slide-out-div').tabSlideOut({
    tabHandle: '.handle',
    pathToTabImage: 'images/historyTab.png',
    imageHeight: '97px',
    imageWidth: '35px', 
    tabLocation: 'right', 
    speed: 300, 
    action: 'click',
    topPos: '95px', 
    fixedPosition: false,
    onLoadSlideOut: true
});
});