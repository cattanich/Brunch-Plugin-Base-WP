// See http://brunch.io for documentation.

module.exports = {
    paths: {
        public: '.'
    },
    // npm: {
    //     globals: {
    //         $: 'jquery'
    //     }
    // },
    files: {
        javascripts: {
            joinTo: {
                'scripts/sitio.js': /^app/,
                'scripts/vendor.js': /^node_modules/
            }
        },
        stylesheets: {
            joinTo: {
                // "styles/sitio.css": /^app/,
                // "styles/vendor.css": /^(?!app)/
                "styles/sitio.css": /sitio.scss/,
                "styles/vendor.css": /vendor.scss/
            }
        },
    },
    modules: {
        autoRequire: {
            // outputFileName : [ entryModule ]
            'scripts/sitio.js': ['initialize']
        }
    },
    plugins: {
        postcss: {
            options: {
                parser: require('postcss-scss'),
            },
            processors: require('autoprefixer')(['last 8 versions'])
        },
        browserSync: {
            // proxy: "http://localhost/wpnewparcel/",
            server: false,
            proxy: "http://localhost/itin",
            // logLevel: "debug",
            port: 9000
        },
        cleancss: {
            specialComments: 0,
            removeEmpty: true
        },
        // css: {
        //     modules: true
        // },
        assetsmanager: {
            copyTo: {
                'styles/fonts': ['node_modules/@fortawesome/fontawesome-free/webfonts/*'],
                'images': ['node_modules/preloader-js/assets/images/*']
            }
        }
    }
};