function go_page(hash, dontAddHash){
    if (CoreData.iframe_provider === 'vk') {
        if (!dontAddHash) {
            CoreData.processed = true;
            VK.callMethod('setLocation', hash, false);
        }
        hash = hash.replace('?', '&');
        var query_str = '';
        var i = hash.indexOf('&');
        if (i !== -1) {
            query_str = hash.substring( i );
            hash = hash.substring(0, i);
        }
        window.location.replace(CoreData.host + hash +'?IFSID='+ CoreData.ifsid + query_str);
    }
}

function start_iframe_fn() {
    if (CoreData.iframe_provider === 'vk') {
        VK.init(
            function() {
                if (!VK.Observer) {
                    VK.Observer = {
                      _subscribers: function() {
                        if (!this._subscribersMap) {
                          this._subscribersMap = {};
                        }
                        return this._subscribersMap;
                      },
                      publish: function(eventName) {
                        var
                          args = Array.prototype.slice.call(arguments),
                          eventName = args.shift(),
                          subscribers = this._subscribers()[eventName],
                          i, j;

                        if (!subscribers) return;

                        for (i = 0, j = subscribers.length; i < j; i++) {
                          if (subscribers[i] != null) {
                            subscribers[i].apply(this, args);
                          }
                        }
                      },
                      subscribe: function(eventName, handler) {
                        var
                          subscribers = this._subscribers();

                        if (typeof handler != 'function') return false;

                        if (!subscribers[eventName]) {
                          subscribers[eventName] = [handler];
                        } else {
                          subscribers[eventName].push(handler);
                        }
                      },
                      unsubscribe: function(eventName, handler) {
                        var
                          subscribers = this._subscribers()[eventName],
                          i, j;

                        if (!subscribers) return false;
                        if (typeof handler == 'function') {
                          for (i = 0, j = subscribers.length; i < j; i++) {
                            if (subscribers[i] == handler) {
                              subscribers[i] = null;
                            }
                          }
                        } else {
                          delete this._subscribers()[eventName];
                        }
                      }
                    };
                }
                
                VK.callMethod('resizeWindow', 1000, 500);
                
                setTimeout(function(){
                    VK.callMethod('resizeWindow', 1000, $(document).height());
                    VK.addCallback('onLocationChanged', function(hash) { if (CoreData.processed === false) { go_page(hash,true); } });
                }, 300);
                
                VK.callMethod('scrollWindow', 0);
                
                VK.callMethod('setTitle', document.title);
                
                VK.callMethod('scrollSubscribe',true);
            },
            function() { console.log('error'); }, '5.8'
        );
    }
}

$(document).ready(function() {
    $(document).on('click', 'a', function (elm) {
        var target = $(this).attr('target');
        var href = $(this).attr('href');
        var cls = $(this).attr('class');
        var hash;
        var i = href.indexOf(CoreData.host);
        
        if (i !== -1) {
            hash = href.split(CoreData.host);
            hash = hash[1];
        } else {
            hash = href;
        }
        
        if ($(this).hasClass('disabled')) return false;

        if (target == '_blank') {
            return true;
        } else if ((href && ('javascript' === href.substring(0, 10) || href.indexOf('#') === 0)) || (cls && cls.substring(0, 3) === 'ui-')) {
            elm.preventDefault();
        } else {
            go_page(hash);
            elm.preventDefault();
        }
    });
});