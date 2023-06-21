import * as THREE from '/plugin/threejs/build/three.module.js';
import { OrbitControls } from '/plugin/threejs/examples/jsm/controls/OrbitControls.js';
let camera, controls, scene, renderer;
 
scene = new THREE.Scene();

//render
renderer = new THREE.WebGLRenderer({ antialias: true });
renderer.setSize(window.innerWidth, window.innerHeight);
document.body.appendChild(renderer.domElement);
//camera
camera = new THREE.PerspectiveCamera(90, window.innerWidth / window.innerHeight, 1, 1000);
//camera.position.x=1;
camera.position.set( 1, 0, 0 );
//controls
controls = new OrbitControls(camera,renderer.domElement);
controls.enableZoom =true; 
controls.enableDamping = true; // an animation loop is required when either damping or auto-rotation are enabled
controls.dampingFactor = 0.05;

controls.screenSpacePanning = false;

controls.minDistance = 100;
//controls.maxDistance = 1000;

//controls.maxPolarAngle = Math.PI / 2;
//CubeTextureLoader
const path = "/plugin/threejs/textures/cube/Park2/";
var materials = [];
var textureLoader;
const urls = [
			   	path + "px.jpg", path + "nx.jpg",
			   	path + "py.jpg", path + "ny.jpg",
			   	path + "pz.jpg", path + "nz.jpg"
			   ];
var textureCube = new THREE.CubeTextureLoader().load( urls );
scene.background = textureCube;
scene.add(camera)

const animate = function() {
	requestAnimationFrame(animate);
	controls.update();
	renderer.render(scene, camera);
};

animate();
