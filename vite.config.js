// import reactRefresh from '@vitejs/plugin-react-refresh';


// export default ({ command }) => ({
//     base: command === 'serve' ? '' : '/build/',
//     publicDir: 'public',
//     build: {
//         manifest: true,
//         cssCodeSplit: true,
//         outDir: 'public/build',
//         rollupOptions: {
//             input: 'resources/js/app.js',
//         },
//     },
//     plugins: [
//         reactRefresh(),
//     ],
// });
// for local
import reactRefresh from '@vitejs/plugin-react-refresh';

export default {
    plugins: [
        reactRefresh()
    ],
    server: {
        port: 3000 // or any other port you want to use
    },
    build: {
        outDir: 'public/build'
    }
}
