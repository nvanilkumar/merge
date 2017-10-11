
<div class="page-header clearfix">
    <div class="pull-left">
        <h4>Campaign Name77777</h4>
    </div>
    <div class="pull-right">
        <a class="btn btn-themeblue" href="{{ url('_admin_create_campaign')}}"><i class="fa fa-plus" aria-hidden="true"></i> Add New Campaign</a> &nbsp; <a class="btn btn-themeblue" href="{{ url('_admin_campaigns_management')}}"><i class="fa fa-angle-double-left" aria-hidden="true"></i> Back to Campaigns</a>
    </div>
</div>
<div class="row row-eq-height">
    <div class="col-lg-5">
        <div class="box box-info" style="height: 100%;">
            <div class="box-header with-border">
                <h3 class="box-title">Information</h3>
                <div class="pull-right">
                    <a href="{{ url('_admin_edit_campaign',{'campaign_id':campaign.campaignId}) }}" class="btn btn-themeblue btn-xs" ><i class="fa fa-pencil" aria-hidden="true"></i> Edit Campaign</a>
                </div>
            </div>
            <div class="box-body" style="display: block; padding: 0px;">
                <table width="100%" class="table table-striped">
                    <tbody>
                        <tr>
                            <td class="text-bold" style="width: 35%;">Name:</td>
                            <td style="width: 65%;">{{ campaign.campaignName }}</td>
                        </tr>

                        <tr>
                            <td class="text-bold">Status:</td>
                            <td>
                                {% if campaign.campaignStatus == 'active' %}
                                <span class="label label-success">Active</span>
                                {% elseif campaign.campaignStatus == 'inactive' %}
                                <span class="label label-danger">Inactive</span>
                                {% endif %}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-bold">Dates:</td>
                            <td><span class="text-bold">{{ campaign.fromDate|date('d-M-Y') }}</span> to <span class="text-bold">{{ campaign.toDate|date('d-M-Y') }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-bold">Timings:</td>
                            <td><span class="text-bold">{{ campaign.fromTime|date('g:i a') }}</span> to <span class="text-bold">{{ campaign.toTime|date('g:i a') }}</span></td>
                        </tr>
                        <tr>
                            <!--
                            <td class="text-bold">Days:</td>
                            <td><span class="badge {% if campaign.monday == 1 %}themeorange{% else %}grey{% endif %}" title="Monday">M</span> <span class="badge {% if campaign.tuesday == 1 %}themeorange{% else %}grey{% endif %}" title="Tuesday">T</span> <span class="badge {% if campaign.wednesday == 1 %}themeorange{% else %}grey{% endif %}" title="Wednesday">W</span> <span class="badge {% if campaign.thursday == 1 %}themeorange{% else %}grey{% endif %}" title="Thursday">T</span> <span class="badge {% if campaign.friday == 1 %}themeorange{% else %}grey{% endif %}" title="Friday">F</span> <span class="badge {% if campaign.saturday == 1 %}themeorange{% else %}grey{% endif %}" title="Saturday">S</span> <span class="badge {% if campaign.sunday == 1 %}themeorange{% else %}grey{% endif %}" title="Sunday">S</span></td>
                        </tr> -->
                        <tr>
                            <td class="text-bold">Created On:</td>
                            <td>{{ campaign.addedOn|date('d-M-Y  g:i:s a') }}</td>
                        </tr>
                        <tr>
                            <td class="text-bold">Last Updated:</td>
                            <td>{{ campaign.updatedOn|date('d-M-Y  g:i:s a') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="box box-info" style="height: 100%;">
            <div class="box-header with-border">
                <h3 class="box-title">Statistics</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" title="Refresh"><i class="fa fa-refresh"></i>
                    </button>
                </div>
            </div>
            <div class="box-body" >

                <div class="row">
                    <div class="col-lg-6 text-center">
                        <div class="chart-responsive">
                            <canvas id="pieChart" height="150"></canvas>
                        </div>
                        <div class=""><h5>Total calls: {{totalCalls}} </h5></div>
                    </div>
                    <div class="col-lg-6">
                        <ul class="chart-legend clearfix">

                            {% for dailStatus  in dailStatastics %}
                                    <li><i class="fa fa-circle " style='color:{{ attribute(dailStatasticsColors, dailStatus.dial_status)}};'>
                                
                                </i> {{dailStatus.dial_status}} 
                                <span class="pull-right badge themeorange"  >{{dailStatus.sec}} </span>
                                    </li>


                            {% endfor %}
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
<div class="spacer20"></div>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-info">
            <div class="box-header with-border clearfix">
                <h3 class="box-title pull-left">Customers({{ campaignData|length }})</h3>
            </div>
            <div class="box-body table-responsive">
                <div class="well well-sm">
                    <form class="form-inline" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="file" name="customer_file" class="form-control" required accept=".csv"/>
                        </div>
                        <input type="submit" name="Upload" class="btn btn-themeblue btn-sm" value="Upload"  />
                    </form>
                </div>
                <table class="table table-bordered table-striped " id="cust_status">
                    <thead>
                        <tr>
                            <th>Member Name </th>
                            <th>Phone Number </th>
                            <th>Company </th>
                            <th>Acc Code </th>
                            {% if isComplete is defined %}
                            <th>Status </th>
                            <th>Duration </th>
                            {% endif %}
                        </tr>
                    </thead>
                    <tbody>
                        {% for customer in campaignData %}
                        <tr>
                            {% if isComplete is defined %}
                            <td><a href="#" id="view_campaign">{{ customer.title }} {{ customer.first_name }} {{ customer.last_name }}</a></td>
                            <td>{{ customer.phone_number }}</td>
                            <td>{{ customer.company }}</td>
                            <td>{{ customer.acc_code }}</td>
                            <td>{{ customer.dial_status }}</td>
                            <td>{{ customer.duration }}</td>
                            {% else %}
                            <td><a href="#" id="view_campaign">{{ customer.customer.title }} {{ customer.customer.firstName }} {{ customer.customer.lastName }}</a></td>
                            <td>{{ customer.customer.phoneNumber }}</td>
                            <td>{{ customer.customer.company }}</td>
                            <td>{{ customer.customer.accCode }}</td>
                            {% endif %}
                        </tr>
                        {% endfor %}
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer"> </div>
            <!-- /.box-footer-->
        </div>
    </div>
</div>


