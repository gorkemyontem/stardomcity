<div ng-controllerTMP="<?php echo $module_id; ?>Controller">
    <input type="hidden" name="cegg_data[<?php echo $module_id; ?>]" ng-value="models.<?php echo $module_id; ?>.added | json" /> 
    <input type="hidden" name="cegg_updateKeywords[<?php echo $module_id; ?>]" ng-value="updateKeywords.<?php echo $module_id; ?>" /> 

    <tabset>
        <tab active="activeResultTabs.<?php echo $module_id; ?>">
            <tab-heading>
                <strong><?php echo $module->getName(); ?></strong> 
                <span ng-show="models.<?php echo $module_id; ?>.added.length" class="label" ng-class="{'label-danger':models.<?php echo $module_id; ?>.added_changed, 'label-default':!models.<?php echo $module_id; ?>.added_changed}">{{models.<?php echo $module_id; ?>.added.length}}</span>
            </tab-heading>

            <div class="data_panel">

                <div clas="row">
                    <div class="col-sm-3">
                        <input ng-model="shortcodes.<?php echo $module_id; ?>" select-on-click readonly type="text" class="form-control input-sm" />
                    </div>

                    <div class="col-sm-3">
                        <?php
                        $tpl_manager = ContentEgg\application\components\ModuleTemplateManager::getInstance($module_id);
                        $templates = $tpl_manager->getTemplatesList(true);
                        ?>
                        <?php if ($templates): ?>
                            <select ng-model="selectedTemplate_<?php echo $module_id; ?>" ng-change="buildShortcode('<?php echo $module_id; ?>', selectedTemplate_<?php echo $module_id; ?>);">
                                <option value="">- <?php _e('Output Template for shortcode', 'content-egg'); ?> -</option>
                                <?php foreach ($templates as $id => $name): ?>
                                    <option value="<?php echo esc_attr($id); ?>"><?php echo esc_html($name); ?></option>
                                <?php endforeach; ?>
                            </select>                        
                        <?php endif; ?>
                    </div>

                    <div class="col-sm-<?php echo 6; ?> text-right">
                        <a class='btn btn-default btn-sm' ng-click="addBlank('<?php echo $module_id; ?>')">[+] <?php _e('Add offer', 'content-egg'); ?></a>
                        <a class='btn btn-default btn-sm' ng-click="deleteAll('<?php echo $module_id; ?>')" ng-confirm-click="<?php _e('Are you sure you want to delete all results?', 'content-egg'); ?>" ng-disabled='!models.<?php echo $module_id; ?>.added.length'><?php _e('Delete all', 'content-egg'); ?></a>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

            <?php // [RESULTS] ?>
            
            <div ng-init="activeResultTabs['<?php echo $module_id; ?>']=true" ui-sortable="{ 'ui-floating': true }" ng-model="models.<?php echo $module_id; ?>.added" class="row">
                <div class="col-md-12 added_data" ng-repeat="data in models.<?php echo $module_id; ?>.added">
                    <div class="row" style="padding: 5px;">
                        <div class="col-md-1" ng-if="data.img">
                            <img ng-if="data.img" ng-src="{{data.img}}" class="img-responsive" style="max-height: 100px;" />
                        </div>
                        <div ng-class="data.img ? 'col-md-9' : 'col-md-10'">
                            <input type="text" placeholder="<?php _e('Title', 'content-egg'); ?>" ng-model="data.title" class="form-control" style="margin-bottom: 5px;">
                            <div class="row" style="margin:0px;">
                                <div class="col-md-6" style="padding:0px;">
                                    <input type="text" placeholder="<?php _e('Offer URL', 'content-egg'); ?>" ng-model="data.orig_url" class="form-control" style="margin-bottom: 5px;">
                                </div>
                                <div class="col-md-6" style="padding-right:0px;">
                                    <input type="text" placeholder="<?php _e('Deeplink', 'content-egg'); ?>" ng-model="data.extra.deeplink" class="form-control" style="margin-bottom: 5px;">
                                </div>
                            </div>                            
                            <input type="text" placeholder="<?php _e('Image URL', 'content-egg'); ?>" ng-model="data.img" class="form-control" style="margin-bottom: 5px;">
                            <div class="row" style="margin:0px;">
                                <div class="col-md-4" style="padding:0px;">
                                    <input type="text" placeholder="<?php _e('Price', 'content-egg'); ?>" ng-model="data.price" class="form-control">
                                </div>
                                <div class="col-md-1" style="padding-right:0px;">
                                    <select class="form-control" ng-model="data.currencyCode">
                                        <option value="USD">USD</option>
                                        <option value="EUR">EUR</option>
                                        <option value="CAD">CAD</option>
                                        <option value="GBP">GBP</option>
                                        <option value="JPY">JPY</option>
                                        <option value="CNY">CNY</option>
                                        <option value="RUB">RUB</option>
                                        <option value="UAH">UAH</option>
                                        <option value="INR">INR</option>
                                        <option value="AUD">AUD</option>
                                        <option value="VND">VND</option>
                                    </select>                                
                                </div>
                                <div class="col-md-7" style="padding-right:0px;">
                                    <input type="text" placeholder="<?php _e('XPath Price Selector', 'content-egg'); ?>" ng-model="data.extra.priceXpath" class="form-control">

                                </div>                                
                            </div>                               

                            <textarea type="text" placeholder="<?php _e('Description', 'content-egg'); ?>" rows="1" ng-model="data.description" class="col-sm-12" style="margin-top: 5px;"></textarea>
                        </div>
                        <div class="col-md-2">
                            <span ng-if="data.orig_url"><a href="{{data.orig_url}}" target="_blank"><?php _e('Go to ', 'content-egg'); ?></a><br><br></span>
                            <a ng-click="delete(data, '<?php echo $module_id; ?>')"><?php _e('Delete', 'content-egg'); ?></a>                                
                            <span ng-show="data.last_update"><?php _e('Last update: '); ?>{{data.last_update * 1000 | date:'shortDate'}}</span>
                        </div>  
                    </div>
                </div>
            </div>
            <?php // [/RESULTS] ?>
        </tab>        
    </tabset>

</div>