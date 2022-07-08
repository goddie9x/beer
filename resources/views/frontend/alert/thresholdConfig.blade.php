@extends('frontend.layout.main')
@section('content')
    <div class="container my-4">
        <table class="threshold-table bg-dark table-bordered align-middle table-dark table-striped w-100">
            <thead class="table-header">
                <tr>
                    <th class="p-2 text-center" scope="col">#</th>
                    <th class="p-2 text-center" scope="col">Device name</th>
                    <th class="p-2 text-center" scope="col">ceil</th>
                    <th class="p-2 text-center" scope="col">delta ceil</th>
                    <th class="p-2 text-center" scope="col">floor</th>
                    <th class="p-2 text-center" scope="col">delta floor</th>
                    <th class="p-2 text-center" scope="col">Unit</th>
                    <th class="p-2 text-center" scope="col">Action</th>
                </tr>
            </thead>
            <tbody class="list-threshold">
                
            </tbody>
        </table>
    </div>
@endsection
@section('js')
    <script>
        let thresholdsInfo = <?php echo json_encode($thresholds); ?>;
        class Threshold {
            constructor(container = '.list-threshold', {
                index,
                id,
                name,
                ceil,
                delta_ceil,
                floor,
                delta_floor,
                unit,
            }) {
                this.id = id;
                this.name = name;
                this.ceil = ceil;
                this.delta_ceil = delta_ceil;
                this.floor = floor;
                this.delta_floor = delta_floor;
                this.unit = unit;
                this.container = container;
                this.init();
            }
            init() {
                this.mainElementName = 'thresh-' + this.id;
                this.mainElement = document.createElement('tr');
                this.mainElement.classList.add(this.mainElementName);
                this.mainElement.classList.add('text-center');
                $(this.container).append(this.mainElement);
                this.render();
            }
            render() {
                let mainElementClassSelector = '.'+this.mainElementName;
                this.mainElement.innerHTML = `
                <th class="p-1 text-center" scope="row">${this.id}</th>
                <td class="p-1 text-center threshold-name">${this.name}</td>
                `;
                if(this.isEdit){
                    this.mainElement.innerHTML += `
                    <td class="p-1 text-center">
                        <input type="number" class="form-control threshold-ceil" value="${this.ceil}">
                    </td>
                    <td class="p-1 text-center">
                        <input type="number" class="form-control threshold-delta-ceil" value="${this.delta_ceil}">
                    </td>
                    <td class="p-1 text-center">
                        <input type="number" class="form-control threshold-floor" value="${this.floor}">
                    </td>
                    <td class="p-1 text-center">
                        <input type="number" class="form-control threshold-delta-floor" value="${this.delta_floor}">
                    </td>
                    <td class="p-1 text-center">
                        ${this.unit}
                    </td>
                    <td class="text-center">
                        <div class="p-1 d-flex">
                            <div class="btn btn-success mx-1 btn-sm btn-save-threshold">Save</div>
                            <div class="btn btn-danger mx-1 btn-sm btn-cancel-threshold">Cancel</div>
                        </div>
                    </td>
                    `;
                }
                else{
                    this.mainElement.innerHTML += `
                    <td class="p-1 text-center">${this.ceil??''}</td>
                    <td class="p-1 text-center">${this.delta_ceil??''}</td>
                    <td class="p-1 text-center">${this.floor??''}</td>
                    <td class="p-1 text-center">${this.delta_floor??''}</td>
                    <td class="p-1 text-center">${this.unit??''}</td>
                    <td class="p-1 text-center">
                        <div class="btn btn-primary btn-sm btn-edit-threshold">Edit</div>
                    </td>
                    `;
                }
                this.initEventHandle();
            }
            initEventHandle(){
                let mainElementClassSelector = '.'+this.mainElementName;
                $(mainElementClassSelector + ' .btn-edit-threshold').click(() => {
                    this.isEdit = true;
                    this.render();
                });
                $(mainElementClassSelector + ' .btn-save-threshold').click(() => {
                    this.isEdit = false;
                    this.save();
                    this.render();
                });
                $(mainElementClassSelector + ' .btn-cancel-threshold').click(() => {
                    this.isEdit = false;
                    this.render();
                });
            }
            edit() {
                this.isEdit = true;
                this.render();
                this.addCancelBtn();
            }
            save() {
                let mainElementClassSelector = '.'+this.mainElementName;
                let t_ceil = $(mainElementClassSelector + ' .threshold-ceil').val();
                let t_delta_ceil = $(mainElementClassSelector + ' .threshold-delta-ceil').val();
                let t_floor = $(mainElementClassSelector + ' .threshold-floor').val();
                let t_delta_floor = $(mainElementClassSelector + ' .threshold-delta-floor').val();
                
                if(t_ceil == '' || t_delta_ceil == '' || t_floor == '' || t_delta_floor == ''){
                    showToast({
                            type:'danger',
                            title:'Error',
                            message:'Please fill all fields'
                            });
                    return;
                }
                if(t_ceil < t_floor){
                    showToast({
                            type:'danger',
                            title:'Error',
                            message:'ceil must be greater than floor'
                            });
                    return;
                }
                $.ajax({
                    url: '{{ route('frontend.alert.setThreshold') }}',
                    type: 'POST',
                    data: {
                        id: this.id,
                        t_ceil,
                        t_delta_ceil,
                        t_floor,
                        t_delta_floor,
                        _token: '{{ csrf_token() }}'
                    },
                    success: (res)=> {
                        if (res.success) {
                            showToast({
                                title: 'Thành công',
                                message: res.message,
                                type: 'success'
                            });
                            this.isEdit = false;
                            this.ceil = t_ceil;
                            this.delta_ceil = t_delta_ceil;
                            this.floor = t_floor;
                            this.delta_floor = t_delta_floor;
                            this.render();
                        } else {
                            showToast({
                                title: 'Lỗi',
                                message: res.message,
                                type: 'error'
                            });
                        }
                    }
                });

            }
        }
        $(document).ready(function() {
            thresholdsInfo.forEach((threshold,index) => {
                let thresholdObj = {
                    index:index,
                    id: threshold.id,
                    name: threshold.Dev_Name,
                    ceil: threshold.t_ceil,
                    delta_ceil: threshold.t_delta_ceil,
                    floor: threshold.t_floor,
                    delta_floor: threshold.t_delta_floor,
                    unit: threshold.Dev_Unit,
                    isEdit: false
                };
                new Threshold('.list-threshold', thresholdObj);
            });
            $(window).scroll(function() {
                let tableHeader = document.querySelector('.table-header');
                
                if ($(window).scrollTop()> 150) {
                    tableHeader.classList.add('position-sticky');
                    tableHeader.classList.add('text-success');
                    tableHeader.style.top = '55px';
                }
                else {
                    tableHeader.classList.remove('position-sticky');
                    tableHeader.classList.remove('text-success');
                    tableHeader.style.top = '0px';
                }
            });
        });
    </script>
@endsection
