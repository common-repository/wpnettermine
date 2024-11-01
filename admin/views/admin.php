<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   WPNetTermine
 * @author    Marc Nilius <mail@marcnilius.de>
 * @license   GPL-2.0+
 * @link      http://www.marcnilius.de
 * @copyright 2014 Marc Nilius
 */
?>

<div class="wrap">

	<h2><?php _e( 'Settings &rsaquo; WPNetTermine', 'wpnettermine' ) ?></h2>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
                <p>
                    <?php _e( 'Please insert your Object ID of NetTermine here. All other settings are optional.', 'wpnettermine' ) ?>
                </p>
                <p>
                    <?php _e( 'Add the shortcode', 'wpnettermine' ) ?> <code>[nettermine]</code>
                    <?php _e( 'on a page of this WordPress instance to show the NetTermine form there.', 'wpnettermine' ) ?>
                </p>

                <?php
                    $settings = (array) get_option( 'wpnettermine_settings' );
                ?>
                <form method="post" action="options.php">
                    <?php settings_fields( 'wpnettermine' ); ?>
                    <?php do_settings_sections( 'wpnettermine' ); ?>
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wpnettermine_objectid"><?php _e( 'Object ID', 'wpnettermine' ) ?></label>
                                </th>
                                <td>
                                    <input
                                        type="number"
                                        name="wpnettermine_settings[objectid]"
                                        id="wpnettermine_objectid"
                                        value="<?php echo esc_attr ( $settings['objectid'] ); ?>"
                                        class="small-text code"
                                    >
                                    <p class="description">
                                        <?php _e( 'The Object ID is available after your registration for the NetTermine service.', 'wpnettermine' ) ?>
                                    </p>
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">
                                    <label for="wpnettermine_includetype"><?php _e( 'Type of integration', 'wpnettermine' ) ?></label>
                                </th>
                                <td>
                                    <select name="wpnettermine_settings[includetype]" id="wpnettermine_includetype">
                                        <option value="html" <?php selected( $settings['includetype'], 'html', true);?>><?php _e( 'Include as HTML (default)', 'wpnettermine' ) ?></option>
                                        <option value="iframe" <?php selected( $settings['includetype'], 'iframe', true);?>><?php _e( 'Include as IFrame', 'wpnettermine' ) ?></option>
                                    </select>
                                    <p class="description">
                                        <?php _e( 'Please choose how the NetTermine form should be integrated. For a full integration including your CSS styles, choose "Insert as HTML".', 'wpnettermine' ) ?>
                                    </p>
                                </td>
                            </tr>

                            <tr valign="top" id="wpnettermine_row_iframewidth">
                                <th scope="row">
                                    <label for="wpnettermine_iframewidth"><?php _e( 'IFrame width', 'wpnettermine' ) ?></label>
                                </th>
                                <td>
                                    <input
                                        type="number"
                                        name="wpnettermine_settings[iframewidth]"
                                        id="wpnettermine_iframewidth"
                                        value="<?php echo esc_attr ( $settings['iframewidth'] ); ?>"
                                        class="small-text code"
                                    >
                                    <span class="description">px</span>
                                    <p class="description">
                                        <?php _e( 'If you choose "Include as IFrame", please insert the width of the IFrame in pixels.', 'wpnettermine' ) ?>
                                    </p>
                                </td>
                            </tr>

                            <tr valign="top" id="wpnettermine_row_iframeheight">
                                <th scope="row">
                                    <label for="wpnettermine_iframeheight"><?php _e( 'IFrame height', 'wpnettermine' ) ?></label>
                                </th>
                                <td>
                                    <input
                                        type="number"
                                        name="wpnettermine_settings[iframeheight]"
                                        id="wpnettermine_iframeheight"
                                        value="<?php echo esc_attr ( $settings['iframeheight'] ); ?>"
                                        class="small-text code"
                                    >
                                    <span class="description">px</span>
                                    <p class="description">
                                        <?php _e( 'If you choose "Include as IFrame", please insert the height of the IFrame in pixels.', 'wpnettermine' ) ?>
                                    </p>
                                </td>
                            </tr>

                            <tr valign="top" id="wpnettermine_row_styles">
                                <th scope="row">
                                    <label for="wpnettermine_styles"><?php _e( 'Individual styles', 'wpnettermine' ) ?></label>
                                </th>
                                <td>
                                    <textarea id="wpnettermine_styles" name="wpnettermine_settings[styles]" cols="160" rows="10" class="large-text all-options"><?php echo esc_attr ( $settings['styles'] ); ?></textarea>
                                    <p class="description">
                                        <?php _e( 'If you choose "Include as HTML" you can add additional css styles here for the NetTermine form.', 'wpnettermine' ) ?>
                                        <?php _e( 'The form can be referenced by the ID #nettermine.', 'wpnettermine' ) ?>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <?php submit_button(); ?>
                </form>
            </div>

            <!-- sidebar -->
            <div id="postbox-container-1" class="postbox-container">

                <div class="meta-box-sortables">
                    <div class="postbox">
                        <h3><span><?php _e( 'HausManager and NetTermine', 'wpnettermine' ) ?></span></h3>
                        <div class="inside">
                            <p>
                                <?php _e( 'NetTermine and HausManager are products of', 'wpnettermine' ) ?>
                                <a href="http://www.hausmanager.de" target="_blank">Computer-L.A.N. GmbH, Fulda</a>.
                                <?php _e( 'Please contact them for all questions regarding these products.', 'wpnettermine' ) ?>
                            </p>
                            <p>
                                <?php _e( 'WPNetTermine (this plugin) is not a product of Computer-L.A.N. and not associated with them in any way.', 'wpnettermine' ) ?>
                            </p>
                            <p>
                                <strong><?php _e( 'For help with this plugin please always refer to the author of the plugin (see below).', 'wpnettermine' ) ?></strong>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="meta-box-sortables">
                    <div class="postbox">
                        <h3><span><?php _e( 'About the author', 'wpnettermine' ) ?></span></h3>
                        <div class="inside">
                            <p>
                                <?php _e( 'Marc Nilius is a software engineer from Germany.', 'wpnettermine' ) ?>
                                <?php _e( 'Find out more about his projects on his website ', 'wpnettermine' ) ?>
                                <a href="http://www.marcnilius.de" target="_blank">marcnilius.de</a>
                                <?php _e( ', via Twitter (', 'wpnettermine' ) ?><a href="http://www.twitter.com/marcnilius" target="_blank">@marcnilius</a><?php
                                 _e( ') or on Google+ (', 'wpnettermine' ) ?><a href="http://www.google.com/+marcnilius" target="_blank">+marcnilius</a><?php
                                 _e( ').', 'wpnettermine' ) ?>
                            </p>
                            <p>
                                <?php _e( 'Contact him via E-Mail: ', 'wpnettermine' ) ?>
                                <a href="mailto:mail@marcnilius.de" target="_blank">mail@marcnilius.de</a>.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <br class="clear">
    </div>
</div>
