module.exports = function(grunt) {
    grunt.initConfig({
        apidoc: {
            mini_aspire: {
                src: "../domains/",
                dest: "public/",
                options: {
                    debug: true,
                    includeFilters: [".*\\.php$"]
                }
            }
        },
        watch: {
            scripts: {
                files: ['../domains/*/**'],
                tasks: ['apidoc'],
                options: {
                    livereload: true
                }
            }
        }
    });
    grunt.loadNpmTasks('grunt-apidoc');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.registerTask('runwatch', ['watch']);
    grunt.registerTask('default', ['apidoc']);
};
