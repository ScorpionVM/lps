function multi_check(obj, el, nr){
    var name = 'quest'+ nr.toString(); 

    if(el == 0){
        var x = document.getElementsByName(name);
        for(var i = 0; i<x.length; i++){
            if(obj.checked == true){
                if(x[i].checked != true){
                    x[i].checked = false;
                }
            } else {
                x[i].disabled = false;
            }

        }
    }
}

function switch_element(name, a){
    //alert(name);
    var x = document.getElementsByClassName(name.toString() +'st');
    var y = document.getElementsByClassName(name.toString() +'rc');
    if(a == 0){
        for(var i=0; i<x.length; i++){
            x[i].style = 'display: block';
        }
        for(var i=0; i<y.length; i++){
            y[i].style = 'display: none';
        }

    } else if(a == 1) {
        for(var i=0; i<x.length; i++){
            x[i].style = 'display: none';
        }
        for(var i=0; i<y.length; i++){
            y[i].style = 'display: block';
        }
    } else {
        for(var i=0; i<x.length; i++){
            x[i].style = 'display: none';
        }
        for(var i=0; i<y.length; i++){
            y[i].style = 'display: none';
        }
    }
}

function show_recom(key){
    var x = document.getElementsByClassName(key.toString());
    var y = document.getElementsByClassName('per_grupa');
    for(i=0; i < y.length; i++){
        y[i].style = 'display: none';
    }
    for(i=0; i < x.length; i++){
        x[i].style = 'display: table';
    }
}

function contor(elem, ct) {
    var x = document.getElementById("contor_"+ct.toString());

    if(elem.value.length <= 355){
        x.innerHTML = (355-elem.value.length).toString()+'/355';
    } 
}

function redirect_to_mainpage(x){
    if(x == 0){
        window.location.replace("http://localhost:10256/index.php")
    } else {
        document.getElementById("timeleft").innerHTML = x;
        setTimeout(redirect_to_mainpage, 992, [x-1]);
    }
}

function equivalent_data(obj, val){
    
    if(obj.value == val){
        umod.disabled = true;
        div_.style = 'display: block; color: red';
        return 'border-color: red';
    } else {
        umod.disabled = false;
        div_.style = 'display: none; color: red';        
        return 'border-color: initial';
    }
}

function data_exists(obj){
    var x = document.getElementsByClassName('groups_code');
    
    var umod = document.getElementById('upload_modal');
    var div_ = document.getElementById('data_exists');
    
    for(var i = 0; i < x.length; i++){
        if(obj.value == x[i].innerHTML){
            umod.disabled = true;
            div_.style = 'display: block; color: red';
            obj.style = 'border-color: red';
            break;
        } else {
            umod.disabled = false;
            div_.style = 'display: none; color: red';        
            obj.style = 'border-color: initial';
        }
    }
}