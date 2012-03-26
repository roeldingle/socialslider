//////////////////////////////////////// [START] library for module front ////////////////////////////////////////

/**
 * Module & Plugin query library for Builder front
 * @author chan
 */


/**
 * Module selector
 * @param selector null | module query | html element
 * @return Module object (overwrited jQuery object)
 *
 * 1. Usage
 * Module("MODULE_NAME(>SEQUENCE_NUMBER)(>BLOCK_NAME)([GROUP_NUMBER|^])").function_name(args..);  // do NOT pass M$
 *
 * 2. Function define
 * Module.fn.function_name = function(args.. , M$) {
 *     // use M$ if you need
 * }
 */
sdk_Module = function(selector) {
    var jQuerySelector = sdk_Module._getJQuerySelector(selector);
    var instance = $(jQuerySelector);

    // overwrite module functions to jquery instance
    sdk_Module._bindFn(instance, instance, sdk_Module.fn);  // bind function bucket
    return instance;
};

/* Core logic for module query interface */
(function(sdk_Module) {
    
    /**
     * Bind module function bucket to instance
     */
    sdk_Module._bindFn = function(instance, target, fn) {
        
        for(var name in fn) {
            
            var f = fn[name];
            
            // if element is function bucket
            if(typeof f == "object") {
                target[name] = {};
                sdk_Module._bindFn(instance, target[name], f);
            }
            
            // if element is single function
            else if(typeof f == "function")
                sdk_Module._bindF(instance, target, name, f);  // bind function
            
        }
        
    }
    
    /**
     * Bind module function to instance
     */
    sdk_Module._bindF = function(instance, target, fname, f) {
        
        target[fname] = function() {

            var fArgs = Array.prototype.slice.call(arguments);
            var lastIndex = f.length - 1;
            
            var result;
            
            // for each real html elements
            instance.each(function(index) {

                /**
                 * define action for each element
                 */
                var _action = function(element) {
                    
                    // make module query interface
                    var M$ = function(selector) {
                        return $(element).sdk_findOnModule(selector);
                    };
                    
                    var self = sdk_Module(element);
                    $.extend(M$, self);
                    
                    // bind interface for getting module infomation & assigned data
                    M$.info = function(key) {
                        var info = sdk_Module.info(element);
                        info.element = element;  // add element to object
                        info.index = index;  // add index of element to object
                        if(key) return info[key];
                        else return info;
                    }
                    M$.data = function(key) {
                        var data = sdk_Module.data(element);
                        if(key) return data[key];
                        else return data;
                    };
                    
                    // set last argument as module query interface
                    fArgs[lastIndex] = M$;
                    
                    // do function
                    return f.apply(self, fArgs);  // apply with called arguments
                };

                // do action for each element
                result = _action(this);
            });
            
            return result;
        };
    };

    /**
     * Get jquery selector from module selector
     */
    sdk_Module._getJQuerySelector = function(moduleSelector) {
        var jQuerySelector = null;

        // get jquery selector
        if(!moduleSelector) {
            jQuerySelector = ".xans-element-";
        }
        else if(typeof moduleSelector == "string") {
            var info = sdk_Module.decodeQuery(moduleSelector);

            // if module only
            if(info.seq == null && info.block == null)
                jQuerySelector = ".xans-" + info.module;
            // else with seq
            else if(info.seq != null && info.block == null)
                jQuerySelector = ".xans-" + info.module + "-" + info.seq;
            // else with block
            else if(info.seq == null && info.block != null)
                jQuerySelector = ".xans-" + info.module + "-" + info.block;
            // else with seq and block
            else if(info.seq != null && info.block != null)
                jQuerySelector = ".xans-" + info.module + "-" + info.block + "-" + info.seq;


            // if defined group is '^'
            if(info.group == "^")
                jQuerySelector += ":not(:has(input._block_group))";

            // if defined group is exist
            else if(info.group)
                jQuerySelector += ":has(input._block_group[value=" + info.group + "])";
        }
        else {
            return moduleSelector;
        }

        return jQuerySelector;
    };

    /**
     * Decode module query to module information object
     */
    sdk_Module.decodeQuery = function(query) {
        if(query == null) return null;

        // trim
        query = query.replace(/ /g, "").toLowerCase();

        // parse for group number
        var regGroupFinder = /\[([0-9]+|\^)\]/i;
        var group = null;
        var groupResult = query.match(regGroupFinder);
        if(groupResult) {
            group = groupResult[1];
            query = query.replace(regGroupFinder, "");  // remove group code
        }
        
        var words = query.split(">");
        
        var info = {
            module : words[0],
            group : group
        };
        
        // for check is sequence number or NOT
        var regNotNumberChecker = /[^0-9]/gi;

        if(words.length == 2) {
            var seqNumOrBlockName = words[1];
            if(regNotNumberChecker.test(seqNumOrBlockName)) info.block = seqNumOrBlockName;
            else info.seq = seqNumOrBlockName;
        }
        else if(words.length == 3) {
            info.seq = words[1];
            info.block = words[2];
        }

        // return information object
        return info;
    };

    /**
     * Encode module information object to module query
     */
    sdk_Module.encodeInfo = function(info) {
        query = info.module;
        if(info.seq) query += ">" + info.seq;
        if(info.block) query += ">" + info.block;
        if(info.group) query += "[" + info.group + "]";
        else query += "[^]";
        return query;
    };


    /**
     * Extract module information (module name, sequence number, block name) from html element
     */
    sdk_Module.info = function(element) {
        var info = {};
        var classes = $(element).attr("class").split(" ");

        for(var i in classes) {
            // skip common class
            if(classes[i] == "xans-element-") continue;

            // extract module name
            if(info.module == null) {
                var moduleMatched = classes[i].match(/xans-([A-Za-z0-9]+)/i);
                if(moduleMatched) {
                    info.module = moduleMatched[1];
                    continue;
                }
            }

            // extract sequence number
            if(info.seq == null) {
                var seqMatched = classes[i].match(/xans-[A-Za-z0-9]+-([0-9]+)/i);
                if(seqMatched) {
                    info.seq = seqMatched[1];
                    continue;
                }
            }

            // extract block name
            if(info.block == null) {
                var blockMatched = classes[i].match(/xans-[A-Za-z0-9]+-([A-Za-z0-9]+)/i);
                // It doesn't find sequence number because before this, seq is checked and break if found.
                if(blockMatched) {
                    info.block = blockMatched[1];
                    continue;
                }
            }
        }
        
        // extract group number
        info.group = $(element).find("input._block_group").val();
        return info;
    };

    /**
     * Get module assigned data
     */
    sdk_Module.data = function(element, key, value) {
        // if called for get
        if(value == undefined) {
            var hash = $.data(element, ASSIGN_DATA_KEY);
            if(key == undefined) return hash;
            else return hash[key];
        }

        // if called for set
        else {
            // initialize hash
            var hash = $.data(element, ASSIGN_DATA_KEY);
            if(hash == null) hash = {};

            // set data
            hash[key] = value;
            $.data(element, ASSIGN_DATA_KEY, hash);
        }
    };
    ASSIGN_DATA_KEY = "ASSIGN_DATA_KEY";
    
    /**
     * Extend module function
     */
    sdk_Module.extend = function(fpath, f) {
        var words = fpath.split(".");
        
        // initialize bucket if NOT initialized
        var bucket = sdk_Module.fn;
        var i = 0;
        for(; i<words.length - 1; i++) {
            var name = words[i];
            if(bucket[name] == null) bucket[name] = {};
            bucket = bucket[name];
        }
        
        // extend f
        var fname = words[i];
        bucket[fname] = f;
    };
    
    /**
     * Module function bucket
     */
    sdk_Module.fn = {};
    
    
    
    
    
    /* jQuery functions for module front loopup */
    (function($) {

        /**
         * check target element is module(or block) defined element
         */
        $.fn.sdk_isModule = function() {
            return $(this).hasClass("xans-element-");
        };
        
        /**
         * check target element is loop-node
         */
        $.fn.sdk_isNode = function() {
            return $(this).hasClass("xans-record-");
        };
        
        /**
         * get module element that includes target element (if target element is module than return target element)
         */
        $.fn.sdk_currentModule = function() {
            if(this.sdk_isModule()) return $(this);
            else return $(this).sdk_parentModule();
        };
        
        /**
         * get loop-node element that includes target element (if target element is loop-node than return loop-node element)
         */
        $.fn.sdk_currentNode = function() {
            if(this.sdk_isNode()) return $(this);
            else return $(this).sdk_parentNode();
        };

        /**
         * get module element that includes target element (if target element is module than return upper element)
         */
        $.fn.sdk_parentModule = function(moduleSelector) {
            var jQuerySelector = sdk_Module._getJQuerySelector(moduleSelector);
            return $(this).parents(jQuerySelector).eq(0);
        };
        
        /**
         * get loop-node element that includes target element
         */
        $.fn.sdk_parentNode = function() {
            return $(this).parents(".xans-record-").eq(0);
        };
        
        /**
         * find all module(or block) defined elements in target element (do NOT select inner module's module)
         */
        $.fn.sdk_findModule = function(moduleSelector) {
            var jQuerySelector = sdk_Module._getJQuerySelector(moduleSelector);
            var sdk_currentModule = $(this).sdk_currentModule();
            return $(this).find(jQuerySelector).filter(function() {
                return $(this).sdk_parentModule().get(0) == sdk_currentModule.get(0);
            });
        };
        
        /**
         * find all loop-node elements in target element (do NOT select inner node's node)
         */
        $.fn.sdk_findNode = function() {
            var sdk_currentModule = $(this).sdk_currentModule();
            return $(this).find(".xans-record-").filter(function() {
                return $(this).sdk_parentModule().get(0) == sdk_currentModule.get(0);
            });
        };

        /**
         * find elements using jquery selector in target element (do NOT select inner module's element)
         */
        $.fn.sdk_findOnModule = function(jQuerySelector) {
            var sdk_currentModule = $(this).sdk_currentModule();

            // find including current block element
            return $(this).find("*").andSelf().filter(jQuerySelector).filter(function() {
                return $(this).sdk_currentModule().get(0) == sdk_currentModule.get(0);
            });
        };
        
        /**
         * find elements using jquery selector in target element (do NOT select inner module's element)
         */
        $.fn.sdk_findOnNode = function(jQuerySelector) {
            var sdk_currentNode = $(this).sdk_currentNode();

            // find including current block element
            return $(this).find("*").andSelf().filter(jQuerySelector).filter(function() {
                return $(this).sdk_currentNode().get(0) == sdk_currentNode.get(0);
            });
        };

    }) (jQuery);

}) (sdk_Module);










/* Define default module functions */

/**
 * Module loader
 */
sdk_Module.fn.ready = function(f, M$) {
    var instance = this;
    var element = M$.info("element");
    $(document).ready(function() {
        f.call(instance, M$);
    });
};

/**
 * Module loader with loop
 */
sdk_Module.fn.readyLoop = function(f, M$) {
    var instance = this;
    var element = M$.info("element");

    $(document).ready(function() {

        // prepare info
        var info = M$.info();

        // prepare loop data
        var loopData = null;
        {
            // check current block is nspace block & get assigned data
            var loopDataFromParent = null;
            var sdk_parentModule = $(element).sdk_parentModule().get(0);
            var sdk_parentNode = $(element).sdk_parentNode().get(0);
            if(sdk_parentModule) {
                var parentData = sdk_Module.data(sdk_parentModule);
                if(parentData && parentData.loop) {
                    var sdk_parentNodeIndex = $(sdk_parentModule).sdk_findNode().index(sdk_parentNode);  // get index of current included node
                    var loopDataFromParent = parentData.loop[sdk_parentNodeIndex]["@" + info.block];  // get data from node at the index
                }
            }

            if(loopDataFromParent) loopData = loopDataFromParent;
            else if(M$.data()) loopData = M$.data().loop;
        }


        // get nodes
        var nodes = $(element).sdk_findNode();

        // run each of looping node
        nodes.each(function(nodeIndex) {
            var nodeElement = this;

            // make interface
            var NM$ = function(selector) {  // module query for each node
                return $(nodeElement).sdk_findOnNode(selector);
            };

            // bind to interface
            NM$.info = function(key) {
                var nodeInfo = info;
                nodeInfo.node = {
                    index : nodeIndex,  // add index
                    element : nodeElement  // add element
                };
                if(key) return nodeInfo[key];
                else return nodeInfo;
            };

            NM$.data = function(key) {
                var nodeData = loopData[nodeIndex];
                if(key) return nodeData[key];
                else return nodeData;
            };

            f.call(instance, NM$);
        });
    });
};

/**
 * Module data assign
 */
sdk_Module.fn.assign = function(hash, M$) {
    var element = M$.info("element");
    $.each(hash, function(key, value) {
        sdk_Module.data(element, key, value);
    });
};

/**
 * Module set input
 */
sdk_Module.fn.setInput = function(M$)
{
    var sSetInput = M$.data('setInput');
    SetInput.execute(sSetInput, M$);
};

/**
 * set event
 */
sdk_Module.fn.event = function(sTargetElement, sEventType, fCallFunction, M$)
{
    M$('.class_'+sTargetElement).bind(sEventType, fCallFunction);
};

//////////////////////////////////////// [END] library for module front ////////////////////////////////////////