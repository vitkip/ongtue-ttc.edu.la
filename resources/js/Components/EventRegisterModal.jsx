import { useForm } from '@inertiajs/react';
import { url } from '../lib/url';

export default function EventRegisterModal({ event, onClose }) {
    const { data, setData, post, processing, errors, recentlySuccessful, reset } = useForm({
        full_name: '',
        phone: '',
        email: '',
        subject: event ? `ລົງທະບຽນເຂົ້າຮ່ວມກິດຈະກຳ: ${event.title}` : '',
        message: '',
    });

    if (!event) return null;

    const submit = (e) => {
        e.preventDefault();
        post(url('/inquiries'), {
            preserveScroll: true,
            onSuccess: () => reset('full_name', 'phone', 'email', 'message'),
        });
    };

    return (
        <div
            className="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
            onClick={(e) => e.target === e.currentTarget && onClose()}
        >
            <div className="bg-surface rounded-2xl max-w-lg w-full p-6 md:p-8 shadow-2xl relative border border-outline-variant/30">
                <button onClick={onClose} className="absolute top-4 right-4 text-on-surface-variant hover:text-academic-maroon">
                    <span className="material-symbols-outlined text-[24px]">close</span>
                </button>
                <div className="flex items-center gap-3 mb-4">
                    <div className="w-10 h-10 rounded-full bg-monastic-saffron/20 text-monastic-saffron flex items-center justify-center">
                        <span className="material-symbols-outlined">edit_calendar</span>
                    </div>
                    <div>
                        <h3 className="font-headline-lg text-[20px] text-academic-maroon leading-tight">ລົງທະບຽນເຂົ້າຮ່ວມກິດຈະກຳ</h3>
                        <p className="font-label-caps text-[11px] text-on-surface-variant line-clamp-1">{event.title}</p>
                    </div>
                </div>

                {recentlySuccessful ? (
                    <div className="text-center py-8">
                        <div className="w-16 h-16 bg-monastic-saffron/20 text-monastic-saffron rounded-full flex items-center justify-center mx-auto mb-4">
                            <span className="material-symbols-outlined text-[36px]">check_circle</span>
                        </div>
                        <h4 className="font-headline-lg text-[22px] text-academic-maroon mb-2">ລົງທະບຽນສຳເລັດ!</h4>
                        <p className="font-body-md text-on-surface-variant">ທາງວິທະຍາໄລຈະຕິດຕໍ່ກັບຫາທ່ານໂດຍໄວທີ່ສຸດ. ຂໍຂອບໃຈ!</p>
                    </div>
                ) : (
                    <form onSubmit={submit} className="flex flex-col gap-4 mt-4">
                        <div>
                            <label className="block font-label-caps text-[12px] text-on-surface mb-1">ຊື່ ແລະ ນາມສະກຸນ *</label>
                            <input
                                type="text"
                                required
                                value={data.full_name}
                                onChange={(e) => setData('full_name', e.target.value)}
                                className="w-full px-4 py-2.5 rounded-lg border border-outline-variant bg-surface-container-lowest focus:border-academic-maroon focus:outline-none text-on-surface font-body-md"
                                placeholder="ປ້ອນຊື່ ແລະ ນາມສະກຸນ"
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
                                    placeholder="020 xxxx xxxx"
                                />
                                {errors.phone && <p className="text-error text-[12px] mt-1">{errors.phone}</p>}
                            </div>
                            <div>
                                <label className="block font-label-caps text-[12px] text-on-surface mb-1">ອີເມວ *</label>
                                <input
                                    type="email"
                                    required
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    className="w-full px-4 py-2.5 rounded-lg border border-outline-variant bg-surface-container-lowest focus:border-academic-maroon focus:outline-none text-on-surface font-body-md"
                                    placeholder="example@gmail.com"
                                />
                                {errors.email && <p className="text-error text-[12px] mt-1">{errors.email}</p>}
                            </div>
                        </div>
                        <div>
                            <label className="block font-label-caps text-[12px] text-on-surface mb-1">ຂໍ້ຄວາມເພີ່ມເຕີມ *</label>
                            <textarea
                                rows={3}
                                required
                                value={data.message}
                                onChange={(e) => setData('message', e.target.value)}
                                className="w-full px-4 py-2.5 rounded-lg border border-outline-variant bg-surface-container-lowest focus:border-academic-maroon focus:outline-none text-on-surface font-body-md"
                                placeholder="ຝາກຂໍ້ຄວາມ ຫຼື ຄຳຖາມກ່ຽວກັບກິດຈະກຳ..."
                            />
                            {errors.message && <p className="text-error text-[12px] mt-1">{errors.message}</p>}
                        </div>
                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full py-3 bg-academic-maroon text-on-primary font-label-caps font-bold rounded-lg hover:bg-primary transition-all shadow-md mt-2 flex items-center justify-center gap-2 disabled:opacity-60"
                        >
                            <span>ຢືນຢັນລົງທະບຽນ</span>
                            <span className="material-symbols-outlined text-[18px]">send</span>
                        </button>
                    </form>
                )}
            </div>
        </div>
    );
}
