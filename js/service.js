// VARIABLES -------------------------------------------------------------------

/* global fetch, URLSearchParams, Jcrop */
const urlParams = new URLSearchParams(window.location.search);
const name = urlParams.get('name');
let url = 'https://informatica.ieszaidinvergeles.org:10050/PIA/RekognitionPrueba/service/service.php?name=' + name;
let jcrop = Jcrop.attach('imagen',
        {
            shadeColor: 'black',
            multi:true
        });
let fblur = document.getElementById('fblur');

// LLAMADA AL SERVIDOR ---------------------------------------------------------
fetch(url)
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        console.log(data);
        processResponse(data);
    })
    .catch(function (error) {
        console.log('Request failed', error);
    });



// FUNCIONES -------------------------------------------------------------------

function processResponse(faces) {
    const imagen = document.getElementById('imagen');
    const height = imagen.height;
    const width = imagen.width;
    
    let rect;
    
    for(const face of faces){
        if(face.low < 18) {
            rect = Jcrop.Rect.create(face.Left*width, face.Top*height, face.Width*width, face.height*height);
            jcrop.newWidget(rect, {});
        }
    }
}

function addInput(name, value) {
    let element = document.createElement('input');
    element.name = name + '[]';
    element.type = "hidden";
    element.value = value;
    element.form = 'fblur';
    fblur.appendChild(element);
}


fblur.addEventListener('submit', function() {
    for(const crops of jcrop.crops) {
        addInput('x', crops.pos.x);
        addInput('y', crops.pos.y);
        addInput('w', crops.pos.w);
        addInput('h', crops.pos.h);
    }
})