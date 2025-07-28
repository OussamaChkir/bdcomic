<?php
wp_enqueue_style('block-text', get_template_directory_uri() . '/assets/css/ContentElements/ce-text.css', array(), '1.0', 'all');

$common_properties = get_field('common_properties');
    $background_color = $common_properties['background_color'] ?? ''; 
    $show_in_anchor_navi = $common_properties['show_in_anchor_navi'] ?? false;
    $anchor_navi_label = $common_properties['anchor_navi_label'] ?? '';

$salesforce_contact_form = get_field('salesforce_contact_form');

if ($salesforce_contact_form) :
    $header = $salesforce_contact_form['header'];
    $header_type = $salesforce_contact_form['header_type'];
    $subheader = $salesforce_contact_form['subheader'];
    $text = $salesforce_contact_form['text'];
?>

    <div class="block-text <?php echo $background_color; ?>"<?php if ($show_in_anchor_navi && $anchor_navi_label): ?> id="<?php echo esc_attr(sanitize_title($anchor_navi_label)); ?>"<?php endif; ?>>
        <div class="container">
            <div class="header-wrapper">
                <?php if ($header): ?>
                    <div class="header">
                        <h2 class="<?php echo $header_type; ?>"><?php echo esc_html($header); ?></h2>
                    </div>
                <?php endif; ?>

                <?php if ($subheader): ?>
                    <div class="subheader h3 m-0">
                        <?php echo esc_html($subheader); ?>
                    </div>
                <?php endif; ?>

                <?php if ($text): ?>
                    <div class="text text-medium">
                        <?php echo $text; ?>
                    </div>
                <?php endif; ?>
                
                
                <div class="form-salesforce-container">
                    <form action="https://korschag--fsscdev.sandbox.my.salesforce.com/servlet/servlet.WebToCase?encoding=UTF-8&orgId=00D9V00000FbPbV" method="POST">
                        <input type=hidden name="orgid" value="00D9V00000FbPbV">
                        <input type=hidden name="retURL" value="http://">

                        <!--  ----------------------------------------------------------------------  -->
                        <!--  NOTE: These fields are optional debugging elements. Please uncomment    -->
                        <!--  these lines if you wish to test in debug mode.                          -->
                        <!--  <input type="hidden" name="debug" value=1>                              -->
                        <!--  <input type="hidden" name="debugEmail" value="korsch@valuent.io">       -->
                        <!--  ----------------------------------------------------------------------  -->

                        <label for="name">Contact Name</label><input  id="name" maxlength="80" name="name" size="20" type="text" /><br>

                        <label for="email">Email</label><input  id="email" maxlength="80" name="email" size="20" type="text" /><br>

                        <label for="phone">Phone</label><input  id="phone" maxlength="40" name="phone" size="20" type="text" /><br>

                        <label for="subject">Subject</label><input  id="subject" maxlength="80" name="subject" size="20" type="text" /><br>

                        <label for="description">Description</label><textarea name="description"></textarea><br>

                        <label for="company">Company</label><input  id="company" maxlength="80" name="company" size="20" type="text" /><br>

                        Machine SerialNumber:<input  id="00N9V00000Mx00v" maxlength="100" name="00N9V00000Mx00v" size="20" type="text" /><br>

                        Machine Type:<input  id="00N9V00000Mx2CP" maxlength="100" name="00N9V00000Mx2CP" size="20" type="text" /><br>

                        Country:<select  id="00N9V00000Mx4J3" name="00N9V00000Mx4J3" title="Country"><option value="">--None--</option><option value="United Arab Emirates">United Arab Emirates</option>
                        <option value="Afghanistan">Afghanistan</option>
                        <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                        <option value="Anguilla">Anguilla</option>
                        <option value="Albania">Albania</option>
                        <option value="Armenia">Armenia</option>
                        <option value="Angola">Angola</option>
                        <option value="Antarctica">Antarctica</option>
                        <option value="Argentina">Argentina</option>
                        <option value="American Samoa">American Samoa</option>
                        <option value="Austria">Austria</option>
                        <option value="Australia">Australia</option>
                        <option value="Aruba">Aruba</option>
                        <option value="Aland Islands">Aland Islands</option>
                        <option value="Azerbaijan">Azerbaijan</option>
                        <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                        <option value="Barbados">Barbados</option>
                        <option value="Bangladesh">Bangladesh</option>
                        <option value="Belgium">Belgium</option>
                        <option value="Burkina Faso">Burkina Faso</option>
                        <option value="Bulgaria">Bulgaria</option>
                        <option value="Bahrain">Bahrain</option>
                        <option value="Burundi">Burundi</option>
                        <option value="Benin">Benin</option>
                        <option value="Saint Barthélemy">Saint Barthélemy</option>
                        <option value="Bermuda">Bermuda</option>
                        <option value="Brunei Darussalam">Brunei Darussalam</option>
                        <option value="Bolivia, Plurinational State of">Bolivia, Plurinational State of</option>
                        <option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
                        <option value="Brazil">Brazil</option>
                        <option value="Bahamas">Bahamas</option>
                        <option value="Bhutan">Bhutan</option>
                        <option value="Bouvet Island">Bouvet Island</option>
                        <option value="Botswana">Botswana</option>
                        <option value="Belarus">Belarus</option>
                        <option value="Belize">Belize</option>
                        <option value="Canada">Canada</option>
                        <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                        <option value="Congo, the Democratic Republic of the">Congo, the Democratic Republic of the</option>
                        <option value="Central African Republic">Central African Republic</option>
                        <option value="Congo">Congo</option>
                        <option value="Switzerland">Switzerland</option>
                        <option value="Cote d&#39;Ivoire">Cote d&#39;Ivoire</option>
                        <option value="Cook Islands">Cook Islands</option>
                        <option value="Chile">Chile</option>
                        <option value="Cameroon">Cameroon</option>
                        <option value="China">China</option>
                        <option value="Colombia">Colombia</option>
                        <option value="Costa Rica">Costa Rica</option>
                        <option value="Cuba">Cuba</option>
                        <option value="Cape Verde">Cape Verde</option>
                        <option value="Curaçao">Curaçao</option>
                        <option value="Christmas Island">Christmas Island</option>
                        <option value="Cyprus">Cyprus</option>
                        <option value="Czech Republic">Czech Republic</option>
                        <option value="Germany">Germany</option>
                        <option value="Djibouti">Djibouti</option>
                        <option value="Denmark">Denmark</option>
                        <option value="Dominica">Dominica</option>
                        <option value="Dominican Republic">Dominican Republic</option>
                        <option value="Algeria">Algeria</option>
                        <option value="Ecuador">Ecuador</option>
                        <option value="Estonia">Estonia</option>
                        <option value="Egypt">Egypt</option>
                        <option value="Western Sahara">Western Sahara</option>
                        <option value="Eritrea">Eritrea</option>
                        <option value="Spain">Spain</option>
                        <option value="Ethiopia">Ethiopia</option>
                        <option value="Finland">Finland</option>
                        <option value="Fiji">Fiji</option>
                        <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                        <option value="Micronesia (Federated States of)">Micronesia (Federated States of)</option>
                        <option value="Faroe Islands">Faroe Islands</option>
                        <option value="France">France</option>
                        <option value="Gabon">Gabon</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="Grenada">Grenada</option>
                        <option value="Georgia">Georgia</option>
                        <option value="French Guiana">French Guiana</option>
                        <option value="Guernsey">Guernsey</option>
                        <option value="Ghana">Ghana</option>
                        <option value="Gibraltar">Gibraltar</option>
                        <option value="Greenland">Greenland</option>
                        <option value="Gambia">Gambia</option>
                        <option value="Guinea">Guinea</option>
                        <option value="Guadeloupe">Guadeloupe</option>
                        <option value="Equatorial Guinea">Equatorial Guinea</option>
                        <option value="Greece">Greece</option>
                        <option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
                        <option value="Guatemala">Guatemala</option>
                        <option value="Guam">Guam</option>
                        <option value="Guinea-Bissau">Guinea-Bissau</option>
                        <option value="Guyana">Guyana</option>
                        <option value="China, Hong Kong Special Administrative Region">China, Hong Kong Special Administrative Region</option>
                        <option value="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option>
                        <option value="Honduras">Honduras</option>
                        <option value="Croatia">Croatia</option>
                        <option value="Haiti">Haiti</option>
                        <option value="Hungary">Hungary</option>
                        <option value="Indonesia">Indonesia</option>
                        <option value="Ireland">Ireland</option>
                        <option value="Israel">Israel</option>
                        <option value="Isle of Man">Isle of Man</option>
                        <option value="India">India</option>
                        <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                        <option value="Iraq">Iraq</option>
                        <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                        <option value="Iceland">Iceland</option>
                        <option value="Italy">Italy</option>
                        <option value="Jersey">Jersey</option>
                        <option value="Jamaica">Jamaica</option>
                        <option value="Jordan">Jordan</option>
                        <option value="Japan">Japan</option>
                        <option value="Kenya">Kenya</option>
                        <option value="Kyrgyzstan">Kyrgyzstan</option>
                        <option value="Cambodia">Cambodia</option>
                        <option value="Kiribati">Kiribati</option>
                        <option value="Comoros">Comoros</option>
                        <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                        <option value="Korea, Democratic People&#39;s Republic of">Korea, Democratic People&#39;s Republic of</option>
                        <option value="Korea, Republic of">Korea, Republic of</option>
                        <option value="Kuwait">Kuwait</option>
                        <option value="Cayman Islands">Cayman Islands</option>
                        <option value="Kazakhstan">Kazakhstan</option>
                        <option value="Lao People&#39;s Democratic Republic">Lao People&#39;s Democratic Republic</option>
                        <option value="Lebanon">Lebanon</option>
                        <option value="Saint Lucia">Saint Lucia</option>
                        <option value="Liechtenstein">Liechtenstein</option>
                        <option value="Sri Lanka">Sri Lanka</option>
                        <option value="Liberia">Liberia</option>
                        <option value="Lesotho">Lesotho</option>
                        <option value="Lithuania">Lithuania</option>
                        <option value="Luxembourg">Luxembourg</option>
                        <option value="Latvia">Latvia</option>
                        <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                        <option value="Morocco">Morocco</option>
                        <option value="Monaco">Monaco</option>
                        <option value="Moldova, Republic of">Moldova, Republic of</option>
                        <option value="Montenegro">Montenegro</option>
                        <option value="Saint Martin (French part)">Saint Martin (French part)</option>
                        <option value="Madagascar">Madagascar</option>
                        <option value="Marshall Islands">Marshall Islands</option>
                        <option value="North Macedonia">North Macedonia</option>
                        <option value="Mali">Mali</option>
                        <option value="Myanmar">Myanmar</option>
                        <option value="Mongolia">Mongolia</option>
                        <option value="Macao">Macao</option>
                        <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                        <option value="Martinique">Martinique</option>
                        <option value="Mauritania">Mauritania</option>
                        <option value="Montserrat">Montserrat</option>
                        <option value="Malta">Malta</option>
                        <option value="Mauritius">Mauritius</option>
                        <option value="Maldives">Maldives</option>
                        <option value="Malawi">Malawi</option>
                        <option value="Mexico">Mexico</option>
                        <option value="Malaysia">Malaysia</option>
                        <option value="Mozambique">Mozambique</option>
                        <option value="Namibia">Namibia</option>
                        <option value="New Caledonia">New Caledonia</option>
                        <option value="Niger">Niger</option>
                        <option value="Norfolk Island">Norfolk Island</option>
                        <option value="Nigeria">Nigeria</option>
                        <option value="Nicaragua">Nicaragua</option>
                        <option value="Netherlands">Netherlands</option>
                        <option value="Norway">Norway</option>
                        <option value="Nepal">Nepal</option>
                        <option value="Nauru">Nauru</option>
                        <option value="Niue">Niue</option>
                        <option value="New Zealand">New Zealand</option>
                        <option value="Oman">Oman</option>
                        <option value="Panama">Panama</option>
                        <option value="Peru">Peru</option>
                        <option value="French Polynesia">French Polynesia</option>
                        <option value="Papua New Guinea">Papua New Guinea</option>
                        <option value="Philippines">Philippines</option>
                        <option value="Pakistan">Pakistan</option>
                        <option value="Poland">Poland</option>
                        <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                        <option value="Pitcairn">Pitcairn</option>
                        <option value="Puerto Rico">Puerto Rico</option>
                        <option value="Palestine">Palestine</option>
                        <option value="Portugal">Portugal</option>
                        <option value="Palau">Palau</option>
                        <option value="Paraguay">Paraguay</option>
                        <option value="Qatar">Qatar</option>
                        <option value="Reunion">Reunion</option>
                        <option value="Romania">Romania</option>
                        <option value="Serbia">Serbia</option>
                        <option value="Russian Federation">Russian Federation</option>
                        <option value="Rwanda">Rwanda</option>
                        <option value="Saudi Arabia">Saudi Arabia</option>
                        <option value="Solomon Islands">Solomon Islands</option>
                        <option value="Seychelles">Seychelles</option>
                        <option value="Sudan">Sudan</option>
                        <option value="Sweden">Sweden</option>
                        <option value="Singapore">Singapore</option>
                        <option value="Saint Helena, Ascension and Tristan da Cunha">Saint Helena, Ascension and Tristan da Cunha</option>
                        <option value="Slovenia">Slovenia</option>
                        <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                        <option value="Slovakia">Slovakia</option>
                        <option value="Sierra Leone">Sierra Leone</option>
                        <option value="San Marino">San Marino</option>
                        <option value="Senegal">Senegal</option>
                        <option value="Somalia">Somalia</option>
                        <option value="Suriname">Suriname</option>
                        <option value="South Sudan">South Sudan</option>
                        <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                        <option value="El Salvador">El Salvador</option>
                        <option value="Sint Maarten (Dutch part)">Sint Maarten (Dutch part)</option>
                        <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                        <option value="Swaziland">Swaziland</option>
                        <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                        <option value="Chad">Chad</option>
                        <option value="French Southern Territories">French Southern Territories</option>
                        <option value="Togo">Togo</option>
                        <option value="Thailand">Thailand</option>
                        <option value="Tajikistan">Tajikistan</option>
                        <option value="Tokelau">Tokelau</option>
                        <option value="Timor-Leste">Timor-Leste</option>
                        <option value="Turkmenistan">Turkmenistan</option>
                        <option value="Tunisia">Tunisia</option>
                        <option value="Tonga">Tonga</option>
                        <option value="Turkey">Turkey</option>
                        <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                        <option value="Tuvalu">Tuvalu</option>
                        <option value="Taiwan">Taiwan</option>
                        <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                        <option value="Ukraine">Ukraine</option>
                        <option value="Uganda">Uganda</option>
                        <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                        <option value="United States">United States</option>
                        <option value="Uruguay">Uruguay</option>
                        <option value="Uzbekistan">Uzbekistan</option>
                        <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                        <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                        <option value="Venezuela, Bolivarian Republic of">Venezuela, Bolivarian Republic of</option>
                        <option value="Virgin Islands, British">Virgin Islands, British</option>
                        <option value="United States Virgin Islands">United States Virgin Islands</option>
                        <option value="Vietnam">Vietnam</option>
                        <option value="Vanuatu">Vanuatu</option>
                        <option value="Wallis and Futuna">Wallis and Futuna</option>
                        <option value="Samoa">Samoa</option>
                        <option value="Kosovo">Kosovo</option>
                        <option value="Yemen">Yemen</option>
                        <option value="Mayotte">Mayotte</option>
                        <option value="South Africa">South Africa</option>
                        <option value="Zambia">Zambia</option>
                        <option value="Zimbabwe">Zimbabwe</option>
                        </select><br>

                        <input type="submit" name="submit">

                        </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>