<div class="modal fade" id="treatment-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Treatment Form </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger treatment_alert"  style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="treatment-form" autocomplete="off">

                    @csrf
                    <input type="hidden" id="treatment_id" name="id">
                    <input type="hidden" id="treatment_appointment_id" name="appointment_id">
                    <div class="form-group">
                        <label class="text-primary">Clinical Notes </label>
                        <textarea name="clinical_notes" rows="8" placeholder="Enter clinical Notes here"
                                  class="form-control" spellcheck="true"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-primary">Procedure </label>
                                <input id="procedure_id" name="procedure" placeholder="Enter procedure"
                                       class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label class="text-primary">Tooth Number </label>
                                <select name="tooth_number" id="tooth_number" class="form-control">
                                    <option value="null"> select tooth number</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                    <option value="24">24</option>
                                    <option value="25">25</option>
                                    <option value="26">26</option>
                                    <option value="27">27</option>
                                    <option value="28">28</option>
                                    <option value="31">31</option>
                                    <option value="32">32</option>
                                    <option value="33">33</option>
                                    <option value="34">34</option>
                                    <option value="35">35</option>
                                    <option value="36">36</option>
                                    <option value="37">37</option>
                                    <option value="38">38</option>
                                    <option value="41">41</option>
                                    <option value="42">42</option>
                                    <option value="">43</option>
                                    <option value="44">44</option>
                                    <option value="45">45</option>
                                    <option value="46">46</option>
                                    <option value="47">47</option>
                                    <option value="48">48</option>
                                    <option value="51">51</option>
                                    <option value="52">52</option>
                                    <option value="53">53</option>
                                    <option value="54">54</option>
                                    <option value="55">55</option>
                                    <option value="61">61</option>
                                    <option value="62">62</option>
                                    <option value="63">63</option>
                                    <option value="64">64</option>
                                    <option value="65">65</option>
                                    <option value="71">71</option>
                                    <option value="72">72</option>
                                    <option value="73">73</option>
                                    <option value="74">74</option>
                                    <option value="75">75</option>
                                    <option value="81">81</option>
                                    <option value="82">82</option>
                                    <option value="83">83</option>
                                    <option value="84">84</option>
                                    <option value="85">85</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-danger" onclick="add_new_tooth()">Add</button>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-primary">Treatment </label>
                                <textarea name="treatment" id="treatment" placeholder="Enter treatment done here" rows="8"
                                          class="form-control"
                                          spellcheck="true"></textarea>
                            </div>
                        </div>

                    </div>


                </form>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" id="btn-treatment" onclick="save_treatment()">Save
                    changes
                </button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



