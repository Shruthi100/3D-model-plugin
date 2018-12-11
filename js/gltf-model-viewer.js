function WPGLTF(model, options) {
  var self = this;
  self.lastRendered = Date.now();
  self.dirty = false;
  self.setDirty = function() 
   {
    self.dirty = true;
   }
  self.init = function(model, options){
    self.stage = document.getElementById(options.id);  
    width = 0;
    pWidth = options.width;
    if (pWidth.indexOf('%')==pWidth.length-1) {
      width = self.stage.offsetWidth * parseInt(pWidth) / 100;
     }
    else {
      width = parseInt(pWidth);
    }
    height = 0;
    pHeight = options.height;
    if (pHeight.indexOf('%')==pHeight.length-1) {
      height = self.stage.offsetHeight * parseInt(pHeight) / 100;
    } 
    else {
      height = parseInt(pHeight);
    }
	    
    self.scene = new THREE.Scene();
    self.camera = new THREE.PerspectiveCamera( options.fov, width / height, 1 , 1000 );
    self.camera.position.x = options.camera[0];
    self.camera.position.y = options.camera[1];
    self.camera.position.z = options.camera[1];
    self.camera.lookAt(new THREE.Vector3(0,0,0));
    self.renderer = new THREE.WebGLRenderer({ alpha: true });
    self.renderer.setSize( 512,512 );
    self.renderer.setClearColor( options.background, options.opacity);
    self.renderer.gammaFactor = 2.2;
    self.renderer.gammaInput = true;      //decode from Gamma to Linear
    self.renderer.gammaOutput = true;
    self.stage.appendChild( self.renderer.domElement );	 
	
    var directionalLight = new THREE.DirectionalLight( options.directionalColor, 1 );
    directionalLight.position.set( options.directionalPosition[0], options.directionalPosition[1], options.directionalPosition[2] );
    self.scene.add( directionalLight );
    var light = new THREE.AmbientLight( options.ambient ); // soft white light
    self.scene.add( light );
		
    self.controls = new THREE.OrbitControls( self.camera, self.stage );
    self.controls.damping = 0.1;
    self.controls.addEventListener( 'change', self.setDirty.bind(self) );
    self.loadGLTF( model , options);
		
}
self.progress = function(event) {
  loaded = event.loaded;
  total = event.total;
  console.log(loaded + " of "+total);
}
self.failure = function() {
  self.stage.innerHTML = 'Could not load model.';
  console.log('Could not load model.');
}

self.loadGLTF = function(model,options){
  console.log('Loading GLTF :' +model);
  onload = function (gltf) {
    mesh = gltf.scene;
    var bbox = new THREE.Box3().setFromObject(mesh);
    var zSize = bbox.max.z - bbox.min.z;
    var ySize = bbox.max.y - bbox.min.y;
    var xSize = bbox.max.x - bbox.min.x;

    var bbZRatio = zSize/221.2;
    var bbYRatio = ySize/106.6;
    var bbXRatio = xSize/313.1;
          
    var bbRatio = bbZRatio;
    if (bbRatio < bbYRatio) bbRatio = bbYRatio;
    if (bbRatio < bbXRatio) bbRatio = bbXRatio;
    console.log("BB ratio " + bbRatio);
    console.log("BBRatio indiv " + bbXRatio + " " + bbYRatio + " " + bbZRatio);
    loadScene.add(mesh);
    self.camera.position.set(0, 500*bbRatio, 500*bbRatio);
    self.controls.update();
    console.log('Object loaded now');
  }
    var loadScene = self.scene;
    var loader = new THREE.GLTFLoader();
    loader.load( model, onload, self.progress, self.failure);
  }
	
self.render = function() {
  requestAnimationFrame( self.render.bind(self) );
  delta = Date.now() - self.lastRendered;
  if (self.dirty && delta > 1000/options.fps) {
    self.renderer.render( self.scene, self.camera );
    self.lastRendered = Date.now();
    self.dirty = false;
 }
}
self.init(model, options);
}
