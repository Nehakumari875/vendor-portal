pimcore.registerNS("pimcore.plugin.LoginBundle");

pimcore.plugin.LoginBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.LoginBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {
        // alert("LoginBundle ready!");
    }
});

var LoginBundlePlugin = new pimcore.plugin.LoginBundle();
