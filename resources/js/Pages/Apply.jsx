import { Head, useForm } from '@inertiajs/react';
import SiteLayout from '../Layouts/SiteLayout';
import { url } from '../lib/url';

const inputClass = 'w-full px-4 py-2.5 rounded-lg border border-outline-variant bg-surface-container-lowest focus:border-academic-maroon focus:outline-none text-on-surface font-body-md';
const labelClass = 'block font-label-caps text-[12px] text-on-surface mb-1';
const errorClass = 'text-error text-[12px] mt-1';

function Field({ label, required, error, children }) {
    return (
        <div>
            <label className={labelClass}>{label}{required ? ' *' : ''}</label>
            {children}
            {error && <p className={errorClass}>{error}</p>}
        </div>
    );
}

export default function Apply({ majors, academicYears, genderOptions, accommodationOptions, provinceOptions, selectedMajorId }) {
    const { data, setData, post, processing, errors, recentlySuccessful, reset } = useForm({
        first_name: '',
        last_name: '',
        gender: '',
        dob: '',
        phone: '',
        email: '',
        village: '',
        district: '',
        province: '',
        previous_school: '',
        accommodation_type: '',
        major_id: selectedMajorId ? String(selectedMajorId) : '',
        academic_year_id: '',
        photo: null,
        certificate: null,
        transcript: null,
    });

    const submit = (e) => {
        e.preventDefault();
        post(url('/applications'), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => reset(),
        });
    };

    return (
        <SiteLayout>
            <Head title="ສະໝັກຮຽນ" />
            <div className="flex flex-col w-full">
                <div className="relative w-full overflow-hidden bg-surface-container py-12">
                    <div className="max-w-container-max mx-auto px-margin-mobile text-center">
                        <span className="font-label-caps text-academic-maroon mb-2 tracking-widest uppercase font-bold block">STUDENT APPLICATION</span>
                        <h1 className="font-headline-display text-4xl md:text-5xl text-on-surface mb-4 font-bold">ໃບສະໝັກຮຽນ</h1>
                        <p className="font-body-lg text-on-surface-variant max-w-2xl mx-auto">
                            ກະລຸນາຕື່ມຂໍ້ມູນລຸ່ມນີ້ໃຫ້ຄົບຖ້ວນເພື່ອສະໝັກເຂົ້າຮຽນຢູ່ວິທະຍາໄລຄູສົງ ອົງຕື້.
                        </p>
                    </div>
                </div>

                <div className="max-w-3xl mx-auto px-margin-mobile w-full py-12">
                    {recentlySuccessful ? (
                        <div className="bg-surface-container-low rounded-xl p-8 text-center border border-outline-variant/30">
                            <div className="w-16 h-16 bg-monastic-saffron/20 text-monastic-saffron rounded-full flex items-center justify-center mx-auto mb-4">
                                <span className="material-symbols-outlined text-[36px]">check_circle</span>
                            </div>
                            <h4 className="font-headline-lg text-[22px] text-academic-maroon mb-2">ສົ່ງໃບສະໝັກສຳເລັດ!</h4>
                            <p className="font-body-md text-on-surface-variant">ທາງວິທະຍາໄລຈະຕິດຕໍ່ກັບຫາທ່ານໂດຍໄວທີ່ສຸດ. ຂໍຂອບໃຈ!</p>
                        </div>
                    ) : (
                        <form onSubmit={submit} className="flex flex-col gap-6">
                            <div className="bg-surface-container-low rounded-xl p-6 border border-outline-variant/30 flex flex-col gap-4">
                                <h2 className="font-headline-lg text-lg text-academic-maroon font-bold border-b border-surface-variant pb-2">ຂໍ້ມູນສ່ວນຕົວ</h2>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <Field label="ຊື່" required error={errors.first_name}>
                                        <input type="text" required value={data.first_name} onChange={(e) => setData('first_name', e.target.value)} className={inputClass} />
                                    </Field>
                                    <Field label="ນາມສະກຸນ" required error={errors.last_name}>
                                        <input type="text" required value={data.last_name} onChange={(e) => setData('last_name', e.target.value)} className={inputClass} />
                                    </Field>
                                    <Field label="ເພດ" required error={errors.gender}>
                                        <select required value={data.gender} onChange={(e) => setData('gender', e.target.value)} className={inputClass}>
                                            <option value="">-- ເລືອກເພດ --</option>
                                            {genderOptions.map((g) => (
                                                <option key={g.value} value={g.value}>{g.label}</option>
                                            ))}
                                        </select>
                                    </Field>
                                    <Field label="ວັນເດືອນປີເກີດ" required error={errors.dob}>
                                        <input type="date" required value={data.dob} onChange={(e) => setData('dob', e.target.value)} className={inputClass} />
                                    </Field>
                                    <Field label="ເບີໂທລະສັບ" error={errors.phone}>
                                        <input type="tel" value={data.phone} onChange={(e) => setData('phone', e.target.value)} className={inputClass} placeholder="020 xxxx xxxx" />
                                    </Field>
                                    <Field label="ອີເມວ" error={errors.email}>
                                        <input type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} className={inputClass} placeholder="example@gmail.com" />
                                    </Field>
                                    <Field label="ບ້ານ" error={errors.village}>
                                        <input type="text" value={data.village} onChange={(e) => setData('village', e.target.value)} className={inputClass} />
                                    </Field>
                                    <Field label="ເມືອງ" error={errors.district}>
                                        <input type="text" value={data.district} onChange={(e) => setData('district', e.target.value)} className={inputClass} />
                                    </Field>
                                    <Field label="ແຂວງ" error={errors.province}>
                                        <select value={data.province} onChange={(e) => setData('province', e.target.value)} className={inputClass}>
                                            <option value="">-- ເລືອກແຂວງ --</option>
                                            {provinceOptions.map((p) => (
                                                <option key={p.value} value={p.value}>{p.label}</option>
                                            ))}
                                        </select>
                                    </Field>
                                </div>
                            </div>

                            <div className="bg-surface-container-low rounded-xl p-6 border border-outline-variant/30 flex flex-col gap-4">
                                <h2 className="font-headline-lg text-lg text-academic-maroon font-bold border-b border-surface-variant pb-2">ຂໍ້ມູນການສຶກສາ</h2>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <Field label="ຈົບມໍປາຍຈາກ" error={errors.previous_school}>
                                        <input type="text" value={data.previous_school} onChange={(e) => setData('previous_school', e.target.value)} className={inputClass} />
                                    </Field>
                                    <Field label="ປະເພດທີ່ພັກ" required error={errors.accommodation_type}>
                                        <select required value={data.accommodation_type} onChange={(e) => setData('accommodation_type', e.target.value)} className={inputClass}>
                                            <option value="">-- ເລືອກປະເພດທີ່ພັກ --</option>
                                            {accommodationOptions.map((a) => (
                                                <option key={a.value} value={a.value}>{a.label}</option>
                                            ))}
                                        </select>
                                    </Field>
                                </div>
                            </div>

                            <div className="bg-surface-container-low rounded-xl p-6 border border-outline-variant/30 flex flex-col gap-4">
                                <h2 className="font-headline-lg text-lg text-academic-maroon font-bold border-b border-surface-variant pb-2">ຂໍ້ມູນການລົງທະບຽນ</h2>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <Field label="ສາຂາ" required error={errors.major_id}>
                                        <select required value={data.major_id} onChange={(e) => setData('major_id', e.target.value)} className={inputClass}>
                                            <option value="">-- ເລືອກສາຂາ --</option>
                                            {majors.map((m) => (
                                                <option key={m.value} value={m.value}>{m.label}</option>
                                            ))}
                                        </select>
                                    </Field>
                                    <Field label="ປີການສຶກສາ" required error={errors.academic_year_id}>
                                        <select required value={data.academic_year_id} onChange={(e) => setData('academic_year_id', e.target.value)} className={inputClass}>
                                            <option value="">-- ເລືອກປີການສຶກສາ --</option>
                                            {academicYears.map((y) => (
                                                <option key={y.value} value={y.value}>{y.label}</option>
                                            ))}
                                        </select>
                                    </Field>
                                </div>
                            </div>

                            <div className="bg-surface-container-low rounded-xl p-6 border border-outline-variant/30 flex flex-col gap-4">
                                <h2 className="font-headline-lg text-lg text-academic-maroon font-bold border-b border-surface-variant pb-2">ເອກະສານ</h2>
                                <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <Field label="ຮູບພາບ" required error={errors.photo}>
                                        <input type="file" accept="image/*" required onChange={(e) => setData('photo', e.target.files[0] ?? null)} className={inputClass} />
                                        <p className="font-body-md text-[11px] text-on-surface-variant mt-1">JPG, PNG, GIF — ບໍ່ເກີນ 5MB</p>
                                    </Field>
                                    <Field label="ໃບຢັ້ງຢືນຈົບການສຶກສາ" error={errors.certificate}>
                                        <input type="file" accept="image/*,application/pdf" onChange={(e) => setData('certificate', e.target.files[0] ?? null)} className={inputClass} />
                                        <p className="font-body-md text-[11px] text-on-surface-variant mt-1">JPG, PNG, GIF, PDF — ບໍ່ເກີນ 10MB</p>
                                    </Field>
                                    <Field label="ໃບຄະແນນ" error={errors.transcript}>
                                        <input type="file" accept="image/*,application/pdf" onChange={(e) => setData('transcript', e.target.files[0] ?? null)} className={inputClass} />
                                        <p className="font-body-md text-[11px] text-on-surface-variant mt-1">JPG, PNG, GIF, PDF — ບໍ່ເກີນ 10MB</p>
                                    </Field>
                                </div>
                            </div>

                            <button
                                type="submit"
                                disabled={processing}
                                className="w-full py-3 bg-academic-maroon text-on-primary font-label-caps font-bold rounded-lg hover:bg-primary transition-all shadow-md flex items-center justify-center gap-2 disabled:opacity-60"
                            >
                                <span>ສົ່ງໃບສະໝັກ (Submit Application)</span>
                                <span className="material-symbols-outlined text-[18px]">send</span>
                            </button>
                        </form>
                    )}
                </div>
            </div>
        </SiteLayout>
    );
}
