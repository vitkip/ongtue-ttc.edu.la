import { useEffect, useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import SiteLayout from '../Layouts/SiteLayout';
import { url } from '../lib/url';
import { badgeColorForCategory } from '../lib/courseBadge';

function CourseCard({ course }) {
    return (
        <div className="bg-surface-container-lowest rounded-xl shadow-md overflow-hidden flex flex-col group border border-outline-variant/30 hover:border-academic-maroon/40 transition-all">
            <div className="relative h-48 bg-surface-container-high overflow-hidden">
                <img className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src={course.image_url} alt={course.title} />
                <div className={`absolute top-4 left-4 px-3 py-1 rounded-full font-label-caps text-[10px] tracking-wider uppercase font-bold ${badgeColorForCategory(course.category)}`}>
                    {course.badge_label}
                </div>
            </div>
            <div className="p-6 flex flex-col flex-grow">
                <h3 className="font-headline-lg text-[22px] text-on-surface mb-2 font-bold">{course.title}</h3>
                <p className="font-body-md text-[14px] text-on-surface-variant mb-4 flex-grow line-clamp-3">{course.description}</p>
                <div className="flex items-center justify-between pt-4 border-t border-outline-variant/20 mt-auto">
                    <span className="text-academic-maroon font-label-caps text-[12px] font-bold">{course.duration_label ?? course.fee_label}</span>
                    <Link href={url('/courses')} className="text-monastic-saffron font-label-caps text-[12px] hover:text-academic-maroon font-bold flex items-center gap-1">
                        ລາຍລະອຽດ <span className="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </Link>
                </div>
            </div>
        </div>
    );
}

const defaultHeroes = [
    {
        image_url: 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=1920&q=80',
        badge_text: 'ພຸດທະສາສະໜາ ແລະ ການສຶກສາ',
        title_line1: 'ສູນກາງການສຶກສາພຸດທະສາດ',
        title_line2: 'ທີ່ເປັນເລີດທາງດ້ານວິຊາການ',
        description: 'ວິທະຍາໄລຄູສົງ ອົງຕື້ ມຸ່ງໝັ້ນສ້າງຊັບພະຍາກອນມະນຸດ ທີ່ມີຄວາມຮູ້ທາງໂລກ ແລະ ທາງທຳ ຄຽງຄູ່ກັນ, ສົ່ງເສີມການຮຽນຮູ້ເພື່ອສັນຕິພາບ ແລະ ປັນຍາໃນສັງຄົມຍຸກໃໝ່.',
        primary_button_text: 'ສະໝັກຮຽນດຽວນີ້',
        secondary_button_text: 'ທ່ຽວຊົມວິທະຍາໄລ',
        secondary_button_link: '/about-us',
    },
];

const HERO_AUTOPLAY_MS = 6000;
const TESTIMONIAL_AUTOPLAY_MS = 7000;

const defaultFeatures = [
    { icon: 'school', color: 'saffron', title: 'ຄວາມເປັນເລີດທາງວິຊາການ', description: 'ຫຼັກສູດທີ່ໄດ້ຮັບການຮັບຮອງມາດຕະຖານ, ເນັ້ນການຄົ້ນຄວ້າ ແລະ ການປະຕິບັດຈິງໃນທຸກຂະແໜງການ.' },
    { icon: 'self_improvement', color: 'maroon', title: 'ຄຸນຄ່າທາງພຸດທະສາສະໜາ', description: 'ປູກຝັງຈັນຍາບັນ, ຄຸນນະທຳ ແລະ ການຈະເລີນສະຕິ ໃຫ້ເປັນພື້ນຖານຂອງການດຳລົງຊີວິດ.' },
    { icon: 'public', color: 'saffron', title: 'ຊຸມຊົນລະດັບໂລກ', description: 'ເປີດກວ້າງຮັບນັກສຶກສາຈາກທຸກມຸມໂລກ ສ້າງເຄືອຂ່າຍການຮຽນຮູ້ທີ່ຫຼາກຫຼາຍ ແລະ ກວ້າງຂວາງ.' },
    { icon: 'auto_stories', color: 'maroon', title: 'ຫໍສະໝຸດທີ່ທັນສະໄໝ', description: 'ແຫຼ່ງລວບລວມຄຳພີ, ຕຳລາ ແລະ ສື່ການຮຽນການສອນທີ່ຄົບຖ້ວນ ທັງຮູບແບບສິ່ງພິມ ແລະ ດິຈິຕອນ.' },
];

const defaultTestimonials = [
    {
        quote: 'ການສຶກສາທີ່ແທ້ຈິງ ບໍ່ພຽງແຕ່ເປັນການສະສົມຄວາມຮູ້ ແຕ່ເປັນການພັດທະນາຈິດໃຈໃຫ້ມີຄວາມເມດຕາ, ມີສະຕິປັນຍາ ແລະ ສາມາດດຳລົງຊີວິດຮ່ວມກັບຜູ້ອື່ນດ້ວຍຄວາມສັນຕິ.',
        full_name: 'ພະອາຈານໃຫຍ່',
        role: 'ອະທິການບໍດີ ວິທະຍາໄລຄູສົງ ອົງຕື້',
        photo_url: null,
    },
];

function HeroSlider({ heroes }) {
    const slides = heroes && heroes.length > 0 ? heroes : defaultHeroes;
    const hasMultiple = slides.length > 1;
    const [index, setIndex] = useState(0);

    useEffect(() => {
        setIndex(0);
    }, [slides.length]);

    useEffect(() => {
        if (!hasMultiple) return undefined;
        const timer = setInterval(() => {
            setIndex((i) => (i + 1) % slides.length);
        }, HERO_AUTOPLAY_MS);
        return () => clearInterval(timer);
    }, [hasMultiple, slides.length]);

    const prevSlide = () => setIndex((i) => (i - 1 + slides.length) % slides.length);
    const nextSlide = () => setIndex((i) => (i + 1) % slides.length);

    return (
        <section className="relative w-full overflow-hidden bg-surface-container-highest -mt-20 pt-20" style={{ minHeight: '80vh' }}>
            {slides.map((slide, i) => (
                <div
                    key={slide.id ?? i}
                    className={`absolute inset-0 transition-opacity duration-700 ease-in-out ${i === index ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none'}`}
                    aria-hidden={i !== index}
                >
                    <div className="absolute inset-0 z-0">
                        <div className="w-full h-full bg-cover bg-center" style={{ backgroundImage: `url('${slide.image_url}')` }} />
                        <div className="absolute inset-0 bg-gradient-to-r from-surface/95 via-surface/80 to-transparent" />
                    </div>
                    <div className="relative z-10 max-w-container-max mx-auto px-margin-mobile h-full flex flex-col justify-center min-h-[80vh]">
                        <div className="max-w-2xl py-12">
                            {slide.badge_text && (
                                <span className="inline-block px-4 py-1.5 mb-6 rounded-full bg-monastic-saffron/20 text-academic-maroon font-label-caps tracking-wider border border-monastic-saffron/40 font-bold">{slide.badge_text}</span>
                            )}
                            <h1 className="font-headline-display text-4xl md:text-5xl text-on-surface mb-stack-md leading-tight font-bold">
                                {slide.title_line1}
                                {slide.title_line2 && (
                                    <>
                                        <br />
                                        <span className="text-academic-maroon">{slide.title_line2}</span>
                                    </>
                                )}
                            </h1>
                            {slide.description && (
                                <p className="font-body-lg text-on-surface-variant mb-stack-lg max-w-xl leading-relaxed">
                                    {slide.description}
                                </p>
                            )}
                            <div className="flex flex-wrap gap-4">
                                {slide.primary_button_text && (
                                    slide.primary_button_link ? (
                                        <Link href={url(slide.primary_button_link)} className="inline-flex items-center justify-center px-8 py-4 bg-monastic-saffron text-on-primary font-label-caps rounded-lg hover:bg-primary transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-bold">
                                            {slide.primary_button_text}
                                            <span className="material-symbols-outlined ml-2 text-[18px]">arrow_forward</span>
                                        </Link>
                                    ) : (
                                        <Link href={url('/apply')} className="inline-flex items-center justify-center px-8 py-4 bg-monastic-saffron text-on-primary font-label-caps rounded-lg hover:bg-primary transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-bold">
                                            {slide.primary_button_text}
                                            <span className="material-symbols-outlined ml-2 text-[18px]">arrow_forward</span>
                                        </Link>
                                    )
                                )}
                                {slide.secondary_button_text && (
                                    <Link href={url(slide.secondary_button_link || '/about-us')} className="inline-flex items-center justify-center px-8 py-4 bg-transparent text-academic-maroon border-2 border-academic-maroon font-label-caps rounded-lg hover:bg-academic-maroon/10 transition-all font-bold">
                                        {slide.secondary_button_text}
                                        <span className="material-symbols-outlined ml-2 text-[18px]">explore</span>
                                    </Link>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            ))}

            {hasMultiple && (
                <>
                    <button
                        onClick={prevSlide}
                        aria-label="ສະໄລກ່ອນໜ້າ"
                        className="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-surface/70 hover:bg-surface text-on-surface flex items-center justify-center shadow-md transition-colors"
                    >
                        <span className="material-symbols-outlined">chevron_left</span>
                    </button>
                    <button
                        onClick={nextSlide}
                        aria-label="ສະໄລຕໍ່ໄປ"
                        className="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-surface/70 hover:bg-surface text-on-surface flex items-center justify-center shadow-md transition-colors"
                    >
                        <span className="material-symbols-outlined">chevron_right</span>
                    </button>
                    <div className="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex gap-2">
                        {slides.map((_, i) => (
                            <button
                                key={i}
                                onClick={() => setIndex(i)}
                                aria-label={`ໄປສະໄລທີ ${i + 1}`}
                                className={`h-2.5 rounded-full transition-all ${i === index ? 'w-6 bg-monastic-saffron' : 'w-2.5 bg-on-surface/30 hover:bg-on-surface/50'}`}
                            />
                        ))}
                    </div>
                </>
            )}
        </section>
    );
}

function TestimonialSlider({ testimonials }) {
    const slides = testimonials && testimonials.length > 0 ? testimonials : defaultTestimonials;
    const hasMultiple = slides.length > 1;
    const [index, setIndex] = useState(0);

    useEffect(() => {
        setIndex(0);
    }, [slides.length]);

    useEffect(() => {
        if (!hasMultiple) return undefined;
        const timer = setInterval(() => {
            setIndex((i) => (i + 1) % slides.length);
        }, TESTIMONIAL_AUTOPLAY_MS);
        return () => clearInterval(timer);
    }, [hasMultiple, slides.length]);

    const current = slides[index];
    const initials = (current.full_name || '')
        .trim()
        .split(/\s+/)
        .slice(0, 2)
        .map((part) => part[0])
        .join('')
        .toUpperCase();

    return (
        <section className="py-section-gap bg-parchment-bg border-y border-outline-variant/20">
            <div className="max-w-4xl mx-auto px-margin-mobile text-center">
                <div className="mb-6 flex justify-center">
                    <span className="material-symbols-outlined text-[48px] text-academic-maroon opacity-50">format_quote</span>
                </div>
                <blockquote className="font-headline-lg text-2xl md:text-3xl text-on-surface-variant leading-relaxed mb-8 font-bold">
                    "{current.quote}"
                </blockquote>
                <div className="flex flex-col items-center justify-center">
                    {current.photo_url ? (
                        <img
                            src={current.photo_url}
                            alt={current.full_name}
                            className="w-16 h-16 rounded-full object-cover mb-3 shadow-md"
                        />
                    ) : (
                        <div className="w-16 h-16 rounded-full bg-monastic-saffron text-on-primary flex items-center justify-center font-bold text-2xl mb-3 shadow-md">
                            {initials || 'ພອ'}
                        </div>
                    )}
                    <cite className="not-italic font-label-caps text-academic-maroon font-bold text-lg">{current.full_name}</cite>
                    {current.role && (
                        <span className="font-body-md text-on-surface-variant text-[14px]">{current.role}</span>
                    )}
                </div>

                {hasMultiple && (
                    <div className="flex justify-center gap-2 mt-8">
                        {slides.map((_, i) => (
                            <button
                                key={i}
                                onClick={() => setIndex(i)}
                                aria-label={`ໄປຄຳຄິດເຫັນທີ ${i + 1}`}
                                className={`h-2.5 rounded-full transition-all ${i === index ? 'w-6 bg-monastic-saffron' : 'w-2.5 bg-on-surface-variant/30 hover:bg-on-surface-variant/50'}`}
                            />
                        ))}
                    </div>
                )}
            </div>
        </section>
    );
}

export default function Home({ heroes, features, featuredCourses, testimonials }) {
    const featureItems = features && features.length > 0 ? features : defaultFeatures;

    return (
        <SiteLayout>
            <Head title="ໜ້າຫຼັກ" />
            <div className="flex flex-col w-full">
                {/* Hero */}
                <HeroSlider heroes={heroes} />

                {/* Features */}
                <section className="py-section-gap bg-surface relative z-20">
                    <div className="max-w-container-max mx-auto px-margin-mobile">
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter">
                            {featureItems.map((f, index) => {
                                const color = f.color ?? (index % 2 === 0 ? 'saffron' : 'maroon');
                                return (
                                    <div key={f.id ?? f.title} className="group p-6 bg-surface-container-lowest rounded-xl border border-outline-variant/30 hover:border-monastic-saffron/50 transition-all hover:shadow-lg">
                                        <div className={`w-14 h-14 rounded-full flex items-center justify-center mb-stack-md transition-colors ${color === 'saffron' ? 'bg-monastic-saffron/10 text-monastic-saffron group-hover:bg-monastic-saffron group-hover:text-on-primary' : 'bg-academic-maroon/10 text-academic-maroon group-hover:bg-academic-maroon group-hover:text-on-primary'}`}>
                                            <span className="material-symbols-outlined text-[28px]">{f.icon}</span>
                                        </div>
                                        <h3 className="font-headline-lg text-[20px] text-on-surface mb-stack-sm font-bold">{f.title}</h3>
                                        <p className="font-body-md text-on-surface-variant">{f.description ?? f.body}</p>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </section>

                {/* CTA Banner */}
                <section className="py-20 bg-academic-maroon relative overflow-hidden text-on-primary">
                    <div className="max-w-3xl mx-auto px-margin-mobile text-center relative z-10">
                        <h2 className="font-headline-display text-3xl md:text-4xl text-on-primary mb-stack-md font-bold">ເລີ່ມຕົ້ນເສັ້ນທາງແຫ່ງປັນຍາຂອງທ່ານໄດ້ບ່ອນນີ້</h2>
                        <p className="font-body-lg text-primary-fixed mb-stack-lg leading-relaxed">ເປີດຮັບສະໝັກນັກສຶກສາໃໝ່ ສົກຮຽນ 2026-2027. ເຂົ້າຮ່ວມກັບພວກເຮົາເພື່ອພັດທະນາຕົນເອງ.</p>
                        <div className="flex flex-wrap justify-center gap-4 items-center">
                            <Link href={url('/courses')} className="inline-flex items-center px-6 py-3.5 bg-transparent border-2 border-on-primary text-on-primary hover:bg-on-primary hover:text-academic-maroon font-label-caps rounded-lg transition-colors font-bold">
                                ເບິ່ງຫຼັກສູດທັງໝົດ
                            </Link>
                            <span className="text-on-primary font-body-md px-2">ຫຼື</span>
                            <Link href={url('/apply')} className="inline-flex items-center px-6 py-3.5 bg-monastic-saffron text-on-primary font-label-caps rounded-lg hover:bg-primary transition-colors shadow-md font-bold">
                                ສະໝັກເຂົ້າຮຽນອອນລາຍ
                                <span className="material-symbols-outlined ml-2 text-[18px]">open_in_new</span>
                            </Link>
                        </div>
                    </div>
                </section>

                {/* Featured Courses */}
                <section className="py-section-gap bg-surface-container-low">
                    <div className="max-w-container-max mx-auto px-margin-mobile">
                        <div className="flex flex-col md:flex-row justify-between items-end mb-stack-lg gap-4">
                            <div>
                                <span className="text-monastic-saffron font-label-caps tracking-widest mb-2 block font-bold uppercase">ຫຼັກສູດເດັ່ນ</span>
                                <h2 className="font-headline-lg text-3xl text-on-surface font-bold">ຫຼັກສູດປະລິນຍາຕີ</h2>
                            </div>
                            <Link href={url('/courses')} className="group flex items-center text-academic-maroon font-label-caps hover:text-monastic-saffron transition-colors font-bold">
                                ເບິ່ງທັງໝົດ
                                <span className="material-symbols-outlined ml-1 group-hover:translate-x-1 transition-transform">arrow_right_alt</span>
                            </Link>
                        </div>
                        <div className="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
                            {featuredCourses.map((course) => (
                                <CourseCard key={course.id} course={course} />
                            ))}
                        </div>
                    </div>
                </section>

                {/* Testimonial */}
                <TestimonialSlider testimonials={testimonials} />
            </div>
        </SiteLayout>
    );
}
