<div class="col">
    @if (!empty($formulirs))
    @foreach ($formulirs as $key => $formulir)
    <div class="box text-white mb-3">Formulir {{ $formulir->nama_formulir }}</div>
    <div class="text-right">
        <div class="table-responsive table-striped" style="text-align: center; overflow-x: hidden;">
            <table id="example" class="display expandable-table" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="col-6">Isi Berkas</th>
                        <th class="col-6">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($formulir->isiForms as $isiForm)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $isiForm->isi }}</td>
                        <td>
                            <div class="row">
                                <div class="col d-flex justify-content-center">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label" for="lengkap{{ $isiForm->id }}">
                                            <input type="radio" class="form-check-input" name="keterangan[{{ $isiForm->id }}]" id="lengkap{{ $isiForm->id }}" value="lengkap" checked>
                                            Lengkap
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label" for="tidaklengkap{{ $isiForm->id }}">
                                            <input type="radio" class="form-check-input" name="keterangan[{{ $isiForm->id }}]" id="tidaklengkap{{ $isiForm->id }}" value="tidak lengkap">
                                            Tidak Lengkap
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4"> {{ $formulirs->links() }} </div>
    </div>
    @endforeach
    @else
    <p>Tidak ada formulir yang tersedia.</p>
    @endif
</div>
</div>