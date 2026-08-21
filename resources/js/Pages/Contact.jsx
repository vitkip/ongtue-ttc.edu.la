import { Head } from '@inertiajs/react';
import { useForm } from '@inertiajs/react';
import SiteLayout from '../Layouts/SiteLayout';
import { url } from '../lib/url';

export default function Contact() {
    const { data, setData, post, processing, errors, recentlySuccessful, reset } = useForm({
        full_name: '',
        phone: '',
        email: '',
        subject: '',
        message: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(url('/inquiries'), {
            preserveScroll: true,
            onSuccess: () => reset('full_name', 'phone', 'email', 'subject', 'message'),
        });
    };

    return (
        <SiteLayout>
            <Head title="ຕິດຕໍ່ເຮົາ" />
            <div className="flex flex-col w-full">
                <div className="relative w-full overflow-hidden bg-surface-container py-12">
                    <div className="max-w-container-max mx-auto px-margin-mobile text-center">
                        <span className="font-label-caps text-academic-maroon mb-2 tracking-widest uppercase font-bold block">GET IN TOUCH</span>
                        <h1 className="font-headline-display text-4xl md:text-5xl text-on-surface mb-4 font-bold">ຕິດຕໍ່ພົວພັນ</h1>
                        <p className="font-body-lg text-on-surface-variant max-w-2xl mx-auto">
                            ມີຄຳຖາມກ່ຽວກັບຫຼັກສູດ, ການສະໝັກຮຽນ ຫຼື ຕ້ອງການຂໍ້ມູນເພີ່ມເຕີມ? ຕິດຕໍ່ຫາພວກເຮົາໄດ້ໂດຍກົງ.
                        </p>
                    </div>
                </div>

                <div className="max-w-container-max mx-auto px-margin-mobile w-full py-12 grid grid-cols-1 lg:grid-cols-12 gap-gutter">
                    <div className="lg:col-span-5 flex flex-col gap-6">
                        <div className="bg-surface-container-low rounded-xl p-6 border border-outline-variant/30">
                            <h2 className="font-headline-lg text-lg text-academic-maroon mb-4 font-bold">ຂໍ້ມູນຕິດຕໍ່</h2>
                            <div className="flex flex-col gap-4">
                                <div className="flex items-start gap-3">
                                    <span className="material-symbols-outlined text-monastic-saffron">location_on</span>
                                    <span className="font-body-md text-on-surface-variant">ຖະໜົນເສດຖາທິລາດ, ບ້ານວັດຈັນ, ນະຄອນຫຼວງວຽງຈັນ</span>
                                </div>
                                <div className="flex items-center gap-3">
                                    <span className="material-symbols-outlined text-monastic-saffron">call</span>
                                    <span className="font-body-md text-on-surface-variant">+856 21 212 212</span>
                                </div>
                                <div className="flex items-center gap-3">
                                    <span className="material-symbols-outlined text-monastic-saffron">mail</span>
                                    <span className="font-body-md text-on-surface-variant">info@ongtuecollege.edu.la</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="lg:col-span-7">
                        {recentlySuccessful ? (
                            <div className="bg-surface-container-low rounded-xl p-8 text-center border border-outline-variant/30">
                                <div className="w-16 h-16 bg-monastic-saffron/20 text-monastic-saffron rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span className="material-symbols-outlined text-[36px]">check_circle</span>
                                </div>
                                <h4 className="font-headline-lg text-[22px] text-academic-maroon mb-2">ສົ່ງຂໍ້ຄວາມສຳເລັດ!</h4>
                                <p className="font-body-md text-on-surface-variant">ທາງວິທະຍາໄລຈະຕິດຕໍ່ກັບຫາທ່ານໂດຍໄວທີ່ສຸດ. ຂໍຂອບໃຈ!</p>
                            </div>
                        ) : (
                            <form onSubmit={submit} className="bg-surface-container-low rounded-xl p-6 border border-outline-variant/30 flex flex-col gap-4">
                                <div>
                                    <label className="block font-label-caps text-[12px] text-on-surface mb-1">ຊື່ ແລະ ນາມສະກຸນ *</label>
                                    <input
                                        type="text"
                                        required
                                        value={data.full_name}
                                        onChange={(e) => setData('full_name', e.target.value)}
                                        className="w-full px-4 py-2.5 rounded-lg border border-outline-variant bg-surface-container-lowest focus:border-academic-maroon focus:outline-none text-on-surface font-body-md"
                                    />
                                    {errors.full_name && <p className="text-error text-[12px] mt-1">{errors.full_name}</p>}
                                </div>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label className="block font-label-caps text-[12px] text-on-surface mb-1">ເບີໂທລະສັບ</label>
                                        <input
                                            type="tel"
                                            value={data.phone}
                                            onChange={(e) => setData('phone', e.target.value)}
                                            className="w-full px-4 py-2.5 rounded-lg border border-outline-variant bg-surface-container-lowest focus:border-academic-maroon focus:outline-none text-on-surface font-body-md"
                                        />
                                    </div>
                                    <div>
                                        <label className="block font-label-caps text-[12px] text-on-surface mb-1">ອີເມວ *</label>
                                        <input
                                            type="email"
                                            required
                                            value={data.email}
                                            onChange={(e) => setData('email', e.target.value)}
                                            className="w-full px-4 py-2.5 rounded-lg border border-outline-variant bg-surface-container-lowest focus:border-academic-maroon focus:outline-none text-on-surface font-body-md"
                                        />
                                        {errors.email && <p className="text-error text-[12px] mt-1">{errors.email}</p>}
                                    </div>
                                </div>
                                <div>
                                    <label className="block font-label-caps text-[12px] text-on-surface mb-1">ຫົວຂໍ້</label>
                                    <input
                                        type="text"
                                        value={data.subject}
                                        onChange={(e) => setData('subject', e.target.value)}
                                        className="w-full px-4 py-2.5 rounded-lg border border-outline-variant bg-surface-container-lowest focus:border-academic-maroon focus:outline-none text-on-surface font-body-md"
                                    />
                                </div>
                                <div>
                                    <label className="block font-label-caps text-[12px] text-on-surface mb-1">ຂໍ້ຄວາມ *</label>
                                    <textarea
                                        rows={5}
                                        required
                                        value={data.message}
                                        onChange={(e) => setData('message', e.target.value)}
                                        className="w-full px-4 py-2.5 rounded-lg border border-outline-variant bg-surface-container-lowest focus:border-academic-maroon focus:outline-none text-on-surface font-body-md"
                                    />
                                    {errors.message && <p className="text-error text-[12px] mt-1">{errors.message}</p>}
                                </div>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="w-full py-3 bg-academic-maroon text-on-primary font-label-caps font-bold rounded-lg hover:bg-primary transition-all shadow-md mt-2 flex items-center justify-center gap-2 disabled:opacity-60"
                                >
                                    <span>ສົ່ງຂໍ້ຄວາມ</span>
                                    <span className="material-symbols-outlined text-[18px]">send</span>
                                </button>
                            </form>
                        )}
                    </div>
                </div>
            </div>
        </SiteLayout>
    );
}
