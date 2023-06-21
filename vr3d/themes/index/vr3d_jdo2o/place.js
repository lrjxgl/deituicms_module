import * as THREE from '/plugin/threejs/build/three.module.js';
import { OrbitControls } from '/plugin/threejs/build/OrbitControls.js';
let camera, controls, scene, renderer,clock;
 
scene = new THREE.Scene();

//render
renderer = new THREE.WebGLRenderer({ antialias: true });
renderer.setSize(window.innerWidth, window.innerHeight);
document.body.appendChild(renderer.domElement);
//camera
camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
camera.position.set( 100, 0, 0 );
//controls
controls = new OrbitControls(camera,renderer.domElement);
controls.enableZoom =true; 
controls.minDistance = 100;
/*
controls.enableDamping = true;  
 
controls.screenSpacePanning = false; 

 controls.target = new THREE.Vector3(0,0,0);
 controls.autoRotate = false;
 */
 clock = new THREE.Clock();
 controls.addEventListener('change', function(){
	  renderer.render(scene, camera);
 });
//CubeTextureLoader
const urls =vrData;
var textureCube = new THREE.CubeTextureLoader().load( urls );
scene.background = textureCube;
scene.add(camera)

const animate = function() {
	var delta = clock.getDelta();
	controls.update(delta);
	requestAnimationFrame(animate);
	
	renderer.render(scene, camera);
};

animate();
