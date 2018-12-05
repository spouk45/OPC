
    const locations = $.post('/customer/coord',function(coord){
        console.log(coord);
        return coord;
    });
