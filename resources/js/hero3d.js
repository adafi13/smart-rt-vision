import * as THREE from 'three';

export function initHero3D(canvasId = 'hero3d-canvas') {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const container = canvas.parentElement;

    const scene = new THREE.Scene();
    scene.fog = new THREE.Fog(0x0a0915, 9, 22);
    const camera = new THREE.PerspectiveCamera(45, 1, 0.1, 100);
    camera.position.set(0, 0, 11);

    const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

    // Lights — soft colored points for an ambient, premium glow (kept subtle so shapes don't blow out)
    scene.add(new THREE.AmbientLight(0xffffff, 0.45));
    const lightIndigo = new THREE.PointLight(0x6366f1, 22, 26);
    lightIndigo.position.set(-7, 4, 4);
    scene.add(lightIndigo);
    const lightPurple = new THREE.PointLight(0xa855f7, 18, 26);
    lightPurple.position.set(7, -3, 3);
    scene.add(lightPurple);
    const lightCyan = new THREE.PointLight(0x22d3ee, 14, 26);
    lightCyan.position.set(0, 6, -2);
    scene.add(lightCyan);

    const group = new THREE.Group();
    scene.add(group);

    // Shapes are pushed to the far edges/back of the scene so the central
    // text column stays clear and legible — they read as ambient backdrop, not foreground clutter.
    const shapeDefs = [
        { geo: new THREE.TorusKnotGeometry(1.1, 0.35, 140, 16), color: 0x6366f1, pos: [-6.2, 2.4, -3], scale: 0.62 },
        { geo: new THREE.IcosahedronGeometry(1.3, 0), color: 0xa855f7, pos: [6.4, -1.8, -4], scale: 0.58 },
        { geo: new THREE.OctahedronGeometry(1, 1), color: 0x22d3ee, pos: [5.2, 3.4, -5], scale: 0.46 },
        { geo: new THREE.TorusGeometry(0.9, 0.28, 32, 100), color: 0xf472b6, pos: [-5.6, -3.4, -4], scale: 0.46 },
        { geo: new THREE.SphereGeometry(0.7, 32, 32), color: 0x818cf8, pos: [6.8, 1.2, -6], scale: 0.5 },
    ];

    const meshes = shapeDefs.map((def) => {
        const material = new THREE.MeshPhysicalMaterial({
            color: def.color,
            roughness: 0.45,
            metalness: 0.2,
            transmission: 0.1,
            clearcoat: 0.3,
            clearcoatRoughness: 0.4,
            transparent: true,
            opacity: 0.88,
        });
        const mesh = new THREE.Mesh(def.geo, material);
        mesh.position.set(...def.pos);
        mesh.scale.setScalar(def.scale);
        mesh.userData.baseY = def.pos[1];
        mesh.userData.floatSpeed = 0.4 + Math.random() * 0.4;
        mesh.userData.floatOffset = Math.random() * Math.PI * 2;
        mesh.userData.rotSpeed = (Math.random() - 0.5) * 0.25;
        group.add(mesh);
        return mesh;
    });

    let mouseX = 0;
    let mouseY = 0;
    let targetRotX = 0;
    let targetRotY = 0;

    function onPointerMove(e) {
        const rect = container.getBoundingClientRect();
        mouseX = ((e.clientX - rect.left) / rect.width) * 2 - 1;
        mouseY = ((e.clientY - rect.top) / rect.height) * 2 - 1;
        targetRotY = mouseX * 0.12;
        targetRotX = mouseY * 0.07;
    }
    container.addEventListener('pointermove', onPointerMove);

    let scrollFactor = 0;
    function onScroll() {
        scrollFactor = Math.min(window.scrollY / (window.innerHeight * 0.8), 1);
    }
    window.addEventListener('scroll', onScroll, { passive: true });

    function resize() {
        const { clientWidth, clientHeight } = container;
        renderer.setSize(clientWidth, clientHeight, false);
        camera.aspect = clientWidth / Math.max(clientHeight, 1);
        camera.updateProjectionMatrix();
    }
    const resizeObserver = new ResizeObserver(resize);
    resizeObserver.observe(container);
    resize();

    let rafId = null;
    const clock = new THREE.Clock();

    function renderOnce() {
        renderer.render(scene, camera);
    }

    function tick() {
        const t = clock.getElapsedTime();

        meshes.forEach((mesh) => {
            mesh.rotation.x += mesh.userData.rotSpeed * 0.01;
            mesh.rotation.y += mesh.userData.rotSpeed * 0.015;
            mesh.position.y = mesh.userData.baseY + Math.sin(t * mesh.userData.floatSpeed + mesh.userData.floatOffset) * 0.3;
        });

        group.rotation.y += (targetRotY - group.rotation.y) * 0.04;
        group.rotation.x += (targetRotX - group.rotation.x) * 0.04;

        camera.position.y = -scrollFactor * 2.5;
        group.position.z = -scrollFactor * 3;

        renderOnce();
        rafId = requestAnimationFrame(tick);
    }

    function start() {
        if (rafId === null) tick();
    }
    function stop() {
        if (rafId !== null) {
            cancelAnimationFrame(rafId);
            rafId = null;
        }
    }

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) stop();
        else if (!reduceMotion) start();
    });

    if (reduceMotion) {
        renderOnce();
    } else {
        start();
    }
}
