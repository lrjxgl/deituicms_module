import * as THREE from '/plugin/threejs/build/three.module.js';

			import Stats from '/plugin/threejs/examples/jsm/libs/stats.module.js';

			import { GUI } from '/plugin/threejs/examples/jsm/libs/dat.gui.module.js';
			import { EffectComposer } from '/plugin/threejs/examples/jsm/postprocessing/EffectComposer.js';
			import { RenderPass } from '/plugin/threejs/examples/jsm/postprocessing/RenderPass.js';
			import { TexturePass } from '/plugin/threejs/examples/jsm/postprocessing/TexturePass.js';
			import { CubeTexturePass } from '/plugin/threejs/examples/jsm/postprocessing/CubeTexturePass.js';
			import { ShaderPass } from '/plugin/threejs/examples/jsm/postprocessing/ShaderPass.js';
			import { ClearPass } from '/plugin/threejs/examples/jsm/postprocessing/ClearPass.js';
			import { CopyShader } from '/plugin/threejs/examples/jsm/shaders/CopyShader.js';
			import { OrbitControls } from '/plugin/threejs/examples/jsm/controls/OrbitControls.js';

			let scene, renderer, composer;
			let clearPass, texturePass, renderPass;
			let cameraP, cubeTexturePassP;
			let gui, stats;

			const params = {

				clearPass: true,
				clearColor: 'white',
				clearAlpha: 1.0,

				texturePass: true,
				texturePassOpacity: 1.0,

				cubeTexturePass: true,
				cubeTexturePassOpacity: 1.0,

				renderPass: true
			};

			init();
			animate();

			clearGui();

			function clearGui() {

				if ( gui ) gui.destroy();

				gui = new GUI();

				gui.add( params, "clearPass" );
				gui.add( params, "clearColor", [ 'black', 'white', 'blue', 'green', 'red' ] );
				gui.add( params, "clearAlpha", 0, 1 );

				gui.add( params, "texturePass" );
				gui.add( params, "texturePassOpacity", 0, 1 );

				gui.add( params, "cubeTexturePass" );
				gui.add( params, "cubeTexturePassOpacity", 0, 1 );

				gui.add( params, "renderPass" );

				gui.open();

			}

			function init() {

				const container = document.getElementById( "container" );

				const width = window.innerWidth || 1;
				const height = window.innerHeight || 1;
				const aspect = width / height;
				const devicePixelRatio = window.devicePixelRatio || 1;

				renderer = new THREE.WebGLRenderer();
				renderer.setPixelRatio( devicePixelRatio );
				renderer.setSize( width, height );
				document.body.appendChild( renderer.domElement );

				stats = new Stats();
				container.appendChild( stats.dom );

				//

				cameraP = new THREE.PerspectiveCamera( 65, aspect, 1, 10 );
				cameraP.position.z = 7;

				scene = new THREE.Scene();

				const group = new THREE.Group();
				scene.add( group );

				const light = new THREE.PointLight( 0xddffdd, 1.0 );
				light.position.z = 70;
				light.position.y = - 70;
				light.position.x = - 70;
				scene.add( light );

				const light2 = new THREE.PointLight( 0xffdddd, 1.0 );
				light2.position.z = 70;
				light2.position.x = - 70;
				light2.position.y = 70;
				scene.add( light2 );

				const light3 = new THREE.PointLight( 0xddddff, 1.0 );
				light3.position.z = 70;
				light3.position.x = 70;
				light3.position.y = - 70;
				scene.add( light3 );

				const geometry = new THREE.SphereGeometry( 1, 48, 24 );

				const material = new THREE.MeshStandardMaterial();
				material.roughness = 0.5 * Math.random() + 0.25;
				material.metalness = 0;
				material.color.setHSL( Math.random(), 1.0, 0.3 );

				const mesh = new THREE.Mesh( geometry, material );
				group.add( mesh );

				// postprocessing

				const genCubeUrls = function ( prefix, postfix ) {

					return [
						prefix + 'px' + postfix, prefix + 'nx' + postfix,
						prefix + 'py' + postfix, prefix + 'ny' + postfix,
						prefix + 'pz' + postfix, prefix + 'nz' + postfix
					];

				};

				composer = new EffectComposer( renderer );

				clearPass = new ClearPass( params.clearColor, params.clearAlpha );
				composer.addPass( clearPass );

				texturePass = new TexturePass();
				composer.addPass( texturePass );

				const textureLoader = new THREE.TextureLoader();
				textureLoader.load( "/plugin/threejs/examples/textures/hardwood2_diffuse.jpg", function ( map ) {

					texturePass.map = map;

				} );

				cubeTexturePassP = null;

				const ldrUrls = genCubeUrls( "/plugin/threejs/textures/cube/Park2/", ".jpg" );
				new THREE.CubeTextureLoader().load( ldrUrls, function ( ldrCubeMap ) {

					cubeTexturePassP = new CubeTexturePass( cameraP, ldrCubeMap );
					composer.insertPass( cubeTexturePassP, 2 );

				} );

				renderPass = new RenderPass( scene, cameraP );
				renderPass.clear = false;
				composer.addPass( renderPass );

				const copyPass = new ShaderPass( CopyShader );
				composer.addPass( copyPass );

				const controls = new OrbitControls( cameraP, renderer.domElement );
				controls.enableZoom = false;

				window.addEventListener( 'resize', onWindowResize );

			}

			function onWindowResize() {

				const width = window.innerWidth;
				const height = window.innerHeight;
				const aspect = width / height;

				cameraP.aspect = aspect;
				cameraP.updateProjectionMatrix();

				renderer.setSize( width, height );
				composer.setSize( width, height );

			}

			function animate() {

				requestAnimationFrame( animate );

				stats.begin();

				cameraP.updateMatrixWorld( true );

				let newColor = clearPass.clearColor;

				switch ( params.clearColor ) {

					case 'blue': newColor = 0x0000ff; break;
					case 'red': newColor = 0xff0000; break;
					case 'green': newColor = 0x00ff00; break;
					case 'white': newColor = 0xffffff; break;
					case 'black': newColor = 0x000000; break;

				}

				clearPass.enabled = params.clearPass;
				clearPass.clearColor = newColor;
				clearPass.clearAlpha = params.clearAlpha;

				texturePass.enabled = params.texturePass;
				texturePass.opacity = params.texturePassOpacity;

				if ( cubeTexturePassP !== null ) {

					cubeTexturePassP.enabled = params.cubeTexturePass;
					cubeTexturePassP.opacity = params.cubeTexturePassOpacity;

				}

				renderPass.enabled = params.renderPass;

				composer.render();

				stats.end();

			}