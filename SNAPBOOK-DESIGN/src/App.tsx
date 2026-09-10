import { useState } from 'react'

type Page = 'home' | 'profile' | 'booking' | 'client' | 'photographer' | 'admin' | 'login' | 'signup'

const PAGE_ORDER: Page[] = ['home', 'profile', 'booking', 'client', 'photographer', 'admin', 'login', 'signup']

function Nav({ page, go }: { page: Page; go: (p: Page) => void }) {
  return (
    <nav>
      <div className="logo" onClick={() => go('home')}>Snap<span>Book</span></div>
      <div className="nav-center">
        {PAGE_ORDER.map((p) => (
          <button key={p} className={`nb${page === p ? ' on' : ''}`} onClick={() => go(p)}>
            {p === 'home' ? 'Home' : p === 'profile' ? 'Profile' : p === 'booking' ? 'Booking'
              : p === 'client' ? 'Client' : p === 'photographer' ? 'Photographer'
              : p === 'admin' ? 'Admin' : p === 'login' ? 'Login' : 'Sign up'}
          </button>
        ))}
      </div>
      <div className="nav-right">
        <button className="btn-li" onClick={() => go('login')}>Log in</button>
        <button className="btn-gs" onClick={() => go('signup')}>Get started</button>
      </div>
    </nav>
  )
}

function HomePage({ go }: { go: (p: Page) => void }) {
  const [activeTag, setActiveTag] = useState('All')
  const tags = ['All', 'Wedding', 'Portrait', 'Corporate', 'Product', 'Events']
  const photographers = [
    { name: 'Karim Benali', spec: 'Wedding · Casablanca, MA', rat: '4.9 · 87 reviews', price: '2 400 MAD', img: 'a', badge: 'Available', ico: 'ti-camera' },
    { name: 'Sara Moussaoui', spec: 'Portrait · Rabat, MA', rat: '5.0 · 42 reviews', price: '1 800 MAD', img: 'b', badge: 'Top rated', ico: 'ti-aperture' },
    { name: 'Nabil Rahmani', spec: 'Corporate · Marrakech, MA', rat: '4.7 · 18 reviews', price: '3 200 MAD', img: 'c', badge: 'New', ico: 'ti-photo' },
    { name: 'Leila Fassi', spec: 'Events · Fès, MA', rat: '4.8 · 31 reviews', price: '2 000 MAD', img: 'd', badge: '', ico: 'ti-camera' },
    { name: 'Youssef Amrani', spec: 'Product · Casablanca, MA', rat: '4.6 · 24 reviews', price: '1 500 MAD', img: 'e', badge: '', ico: 'ti-aperture' },
    { name: 'Hind Tahiri', spec: 'Portrait · Tanger, MA', rat: '4.9 · 55 reviews', price: '2 200 MAD', img: 'f', badge: '', ico: 'ti-photo' },
  ]

  return (
    <div id="pg-home">
      <div className="hero">
        <div>
          <div className="eyebrow">Professional photography</div>
          <h1 className="ht">Find your<br /><em>perfect</em><br />photographer</h1>
          <p className="hero-sub">Book talented photographers for events, portraits, and commercial projects — instant availability, transparent pricing.</p>
          <div className="cta-row">
            <button className="btn-hero" onClick={() => go('profile')}>
              <i className="ti ti-search" aria-hidden="true" style={{ fontSize: 15 }}></i> Browse photographers
            </button>
            <button className="btn-ol" onClick={() => go('signup')}>Join as photographer</button>
          </div>
          <div className="hero-stats">
            <div><div className="sn">840+</div><div className="sl">Photographers</div></div>
            <div><div className="sn">12K</div><div className="sl">Bookings</div></div>
            <div><div className="sn">4.9</div><div className="sl">Avg. rating</div></div>
          </div>
        </div>
        <div className="hv">
          <div className="ap"></div>
          <div className="hvc hvc-main"><div className="hvi"><div className="hvtag">Wedding · Casablanca</div><div className="hvname">Karim Benali</div><div className="hvstars"><span>★★★★★</span> 4.9 · 87 shoots</div></div></div>
          <div className="hvc hvc-sm"><div className="hvi"><div className="hvtag">Portrait</div><div className="hvname">Sara M.</div><div className="hvstars"><span>★★★★★</span> 5.0</div></div></div>
          <div className="hvc hvc-xs"><div className="hvi"><div className="hvtag">Corporate</div><div className="hvname">Nabil R.</div></div></div>
        </div>
      </div>
      <hr className="div" />
      <div className="sec">
        <div className="sec-lbl">Browse</div>
        <div className="sec-t">Find a photographer</div>
        <div className="sec-sub">Search by specialty, location, or name</div>
        <div className="search-bar">
          <i className="ti ti-search" aria-hidden="true"></i>
          <input type="text" placeholder="Wedding photographer in Casablanca…" />
          <button>Search</button>
        </div>
        <div className="ftags">
          {tags.map(t => (
            <button key={t} className={`ftag${activeTag === t ? ' on' : ''}`} onClick={() => setActiveTag(t)}>{t}</button>
          ))}
        </div>
        <div className="pg-grid">
          {photographers.map((ph) => (
            <div key={ph.name} className="pgc" onClick={() => go('profile')}>
              <div className={`pgc-img ${ph.img}`}>
                {ph.badge && <div className="pgc-badge">{ph.badge}</div>}
                <i className={`ti ${ph.ico} pgc-ico`} aria-hidden="true"></i>
              </div>
              <div className="pgc-body">
                <div className="pgc-name">{ph.name}</div>
                <div className="pgc-spec">{ph.spec}</div>
                <div className="pgc-foot">
                  <div className="pgc-rat"><span>★</span> {ph.rat}</div>
                  <div className="pgc-price">{ph.price}</div>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
      <hr className="div" />
      <div className="sec">
        <div className="sec-lbl">Process</div>
        <div className="sec-t" style={{ marginBottom: '1.5rem' }}>How it works</div>
        <div className="how-grid">
          <div className="how-item"><div className="how-n">01</div><div className="how-t">Search</div><div className="how-d">Browse photographers by specialty, city, and availability. Filter by budget and style.</div></div>
          <div className="how-item"><div className="how-n">02</div><div className="how-t">Book</div><div className="how-d">Select your session date, confirm your request online — no back-and-forth emails.</div></div>
          <div className="how-item"><div className="how-n">03</div><div className="how-t">Review</div><div className="how-d">After your shoot, leave an honest review and help others discover great talent.</div></div>
        </div>
      </div>
      <footer>
        <div className="foot-logo">Snap<span>Book</span></div>
        <div className="foot-copy">© 2025 SnapBook. All rights reserved.</div>
      </footer>
    </div>
  )
}

function ProfilePage({ go }: { go: (p: Page) => void }) {
  const avDays = [
    { n: '', cls: '' }, { n: '', cls: '' }, { n: '', cls: '' },
    { n: '1', cls: 'free' }, { n: '2', cls: 'free' }, { n: '3', cls: 'busy' }, { n: '4', cls: 'free' },
    { n: '5', cls: 'free' }, { n: '6', cls: 'busy' }, { n: '7', cls: 'free' }, { n: '8', cls: 'free' },
    { n: '9', cls: 'free' }, { n: '10', cls: 'busy' }, { n: '11', cls: 'busy' },
    { n: '12', cls: 'free' }, { n: '13', cls: 'free' }, { n: '14', cls: 'today' }, { n: '15', cls: 'free' },
    { n: '16', cls: 'busy' }, { n: '17', cls: 'busy' }, { n: '18', cls: 'free' },
    { n: '19', cls: 'free' }, { n: '20', cls: 'free' }, { n: '21', cls: 'free' }, { n: '22', cls: 'busy' },
    { n: '23', cls: 'free' }, { n: '24', cls: 'busy' }, { n: '25', cls: 'free' },
  ]
  const portGrads = [
    'linear-gradient(135deg,#023661,#011f3a)',
    'linear-gradient(135deg,#3F3A42,#2a272d)',
    'linear-gradient(135deg,#1a1f28,#0f1318)',
    'linear-gradient(135deg,#1e2a1a,#111a0d)',
    'linear-gradient(135deg,#2a1a1a,#1a0d0d)',
    'linear-gradient(135deg,#021a30,#010e1a)',
  ]

  return (
    <div id="pg-profile">
      <div className="prof-wrap">
        <div className="prof-head">
          <div className="avatar"><i className="ti ti-camera" aria-hidden="true"></i></div>
          <div>
            <div className="prof-name">Karim Benali</div>
            <div className="prof-spec">Wedding &amp; Lifestyle Photography</div>
            <div className="prof-loc"><i className="ti ti-map-pin" aria-hidden="true" style={{ color: 'var(--ember)', fontSize: 13 }}></i> Casablanca, Morocco</div>
            <div className="prof-stats">
              <div><div className="ps-n">87</div><div className="ps-l">Shoots</div></div>
              <div><div className="ps-n">4.9</div><div className="ps-l">Rating</div></div>
              <div><div className="ps-n">6yr</div><div className="ps-l">Experience</div></div>
            </div>
          </div>
          <div className="prof-cta">
            <button className="btn-book" onClick={() => go('booking')}>Book a session</button>
            <button className="btn-msg">Send message</button>
          </div>
        </div>
        <div className="prof-body">
          <div>
            <div className="ptab">Portfolio</div>
            <div className="port-grid">
              {portGrads.map((g, i) => <div key={i} className="pt" style={{ background: g }}></div>)}
            </div>
            <div className="ptab">About</div>
            <p className="bio">Based in Casablanca, I specialize in documentary-style wedding photography that captures real, unscripted moments. My approach is minimal and observational — I stay close but invisible, letting light and genuine emotion do the work.</p>
            <p className="bio">I shoot with a two-camera setup and deliver a fully edited gallery within 30 days. Available across Morocco and for international destination weddings.</p>
            <div className="ptab" style={{ marginTop: '1.5rem' }}>Client reviews</div>
            <div className="rev-card"><div className="rev-top"><div className="rev-name">Amina &amp; Rachid</div><div className="rev-stars">★★★★★</div></div><div className="rev-txt">Karim has a rare talent for disappearing into the background while capturing every meaningful moment. Our wedding photos are beyond anything we imagined.</div></div>
            <div className="rev-card"><div className="rev-top"><div className="rev-name">Fatima Zahra</div><div className="rev-stars">★★★★★</div></div><div className="rev-txt">Professional, calm, and genuinely talented. He made everyone feel at ease and the gallery was delivered ahead of schedule.</div></div>
            <div className="rev-card"><div className="rev-top"><div className="rev-name">Mehdi Alaoui</div><div className="rev-stars">★★★★☆</div></div><div className="rev-txt">Great eye for detail. The couple portraits were stunning. Communication was smooth throughout the whole process.</div></div>
          </div>
          <div>
            <div className="ptab">Services</div>
            <div className="serv-item"><div><div className="sname">Full wedding day</div><div className="sdesc">8 hrs · 400+ edited photos</div></div><div className="sprice">8 500 MAD</div></div>
            <div className="serv-item"><div><div className="sname">Half-day coverage</div><div className="sdesc">4 hrs · 200+ edited photos</div></div><div className="sprice">4 800 MAD</div></div>
            <div className="serv-item"><div><div className="sname">Engagement session</div><div className="sdesc">2 hrs · 80+ edited photos</div></div><div className="sprice">2 400 MAD</div></div>
            <div className="serv-item"><div><div className="sname">Portrait session</div><div className="sdesc">1.5 hrs · 50+ edited photos</div></div><div className="sprice">1 600 MAD</div></div>
            <div className="ptab" style={{ marginTop: '1.5rem' }}>Availability — August 2025</div>
            <div className="av-grid">
              {['M','T','W','T','F','S','S'].map((d, i) => <div key={i} className="av-d">{d}</div>)}
              {avDays.map((d, i) => <div key={i} className={`av-c${d.cls ? ' ' + d.cls : ''}`}>{d.n}</div>)}
            </div>
            <div style={{ display: 'flex', gap: 12, marginTop: '.8rem' }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 5, fontSize: 11, color: 'var(--mist)' }}><div style={{ width: 10, height: 10, background: 'rgba(45,138,78,.15)', border: '1px solid rgba(45,138,78,.35)', borderRadius: 1 }}></div>Available</div>
              <div style={{ display: 'flex', alignItems: 'center', gap: 5, fontSize: 11, color: 'var(--mist)' }}><div style={{ width: 10, height: 10, background: 'rgba(163,48,48,.12)', border: '1px solid rgba(163,48,48,.28)', borderRadius: 1 }}></div>Booked</div>
            </div>
            <button className="btn-book" style={{ width: '100%', marginTop: '1.2rem' }} onClick={() => go('booking')}>Book Karim</button>
          </div>
        </div>
      </div>
    </div>
  )
}

function BookingPage({ go }: { go: (p: Page) => void }) {
  const [loc, setLoc] = useState('')
  const [name, setName] = useState('')
  const [phone, setPhone] = useState('')
  const [locErr, setLocErr] = useState(false)
  const [nameErr, setNameErr] = useState(false)
  const [phoneErr, setPhoneErr] = useState(false)
  const [submitted, setSubmitted] = useState(false)

  function submit() {
    let ok = true
    if (!loc.trim()) { setLocErr(true); ok = false } else setLocErr(false)
    if (!name.trim()) { setNameErr(true); ok = false } else setNameErr(false)
    if (!phone.trim()) { setPhoneErr(true); ok = false } else setPhoneErr(false)
    if (!ok) return
    setSubmitted(true)
  }

  return (
    <div id="pg-booking">
      <div className="book-wrap">
        <div className="book-title">Book a session</div>
        <p className="book-sub">Complete your reservation with Karim Benali</p>
        <div className="steps">
          <div className="sd done"><i className="ti ti-check" aria-hidden="true" style={{ fontSize: 12 }}></i></div>
          <div className="sl2 done">Service</div>
          <div className="sline done"></div>
          <div className="sd act">2</div>
          <div className="sl2 act">Details</div>
          <div className="sline"></div>
          <div className="sd todo">3</div>
          <div className="sl2 todo">Payment</div>
          <div className="sline"></div>
          <div className="sd todo">4</div>
          <div className="sl2 todo">Confirm</div>
        </div>

        {!submitted ? (
          <div id="bk-form">
            <div className="fld">
              <label className="lbl">Selected service</label>
              <select className="sel">
                <option>Full wedding day — 8 500 MAD</option>
                <option>Half-day coverage — 4 800 MAD</option>
                <option>Engagement session — 2 400 MAD</option>
                <option>Portrait session — 1 600 MAD</option>
              </select>
            </div>
            <div className="fld-row">
              <div><label className="lbl">Event date</label><input className="inp" type="date" defaultValue="2025-09-15" /></div>
              <div><label className="lbl">Start time</label><input className="inp" type="time" defaultValue="10:00" /></div>
            </div>
            <div className="fld">
              <label className="lbl">Location</label>
              <input className="inp" type="text" placeholder="Venue name or address" value={loc} onChange={e => { setLoc(e.target.value); setLocErr(false) }} />
              {locErr && <div className="err-msg">Location is required.</div>}
            </div>
            <div className="fld-row">
              <div>
                <label className="lbl">Your name</label>
                <input className="inp" type="text" placeholder="Full name" value={name} onChange={e => { setName(e.target.value); setNameErr(false) }} />
                {nameErr && <div className="err-msg">Name is required.</div>}
              </div>
              <div>
                <label className="lbl">Phone</label>
                <input className="inp" type="tel" placeholder="+212 …" value={phone} onChange={e => { setPhone(e.target.value); setPhoneErr(false) }} />
                {phoneErr && <div className="err-msg">Phone is required.</div>}
              </div>
            </div>
            <div className="fld">
              <label className="lbl">Notes for the photographer</label>
              <textarea className="ta" placeholder="Describe your event, special requests, or style preferences…"></textarea>
            </div>
            <div className="sum-box">
              <div className="sum-row"><span className="sum-lbl">Service</span><span>Full wedding day</span></div>
              <div className="sum-row"><span className="sum-lbl">Date</span><span>15 September 2025</span></div>
              <div className="sum-row"><span className="sum-lbl">Duration</span><span>8 hours</span></div>
              <div className="sum-row"><span className="sum-lbl">Photographer</span><span>Karim Benali</span></div>
              <div className="sum-row"><span className="sum-lbl">Total</span><span className="sum-total">8 500 MAD</span></div>
            </div>
            <button className="btn-sub" onClick={submit}>Confirm and send request</button>
          </div>
        ) : (
          <div id="bk-success" style={{ textAlign: 'center', padding: '3rem 0' }}>
            <div style={{ width: 56, height: 56, borderRadius: '50%', background: 'rgba(45,138,78,.12)', border: '1px solid rgba(45,138,78,.3)', display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 1rem', fontSize: 24, color: '#5dbf7e' }}>
              <i className="ti ti-check" aria-hidden="true"></i>
            </div>
            <div style={{ fontFamily: "'Barlow Condensed',sans-serif", fontSize: 28, fontWeight: 700, textTransform: 'uppercase', marginBottom: '.5rem' }}>Request sent</div>
            <p style={{ fontSize: 13, color: 'var(--mist)', marginBottom: '1.5rem' }}>Karim will confirm within 24 hours. Check your bookings in the client dashboard.</p>
            <button className="btn-book" onClick={() => go('client')}>View my bookings</button>
          </div>
        )}
      </div>
    </div>
  )
}

function ClientPage({ go }: { go: (p: Page) => void }) {
  const [activeTab, setActiveTab] = useState('Bookings')
  const tabs = ['Bookings', 'Reviews', 'Profile']

  return (
    <div id="pg-client">
      <div className="dash-wrap">
        <div className="dash-hd">
          <div className="dash-welcome">Welcome back, <span>Amina</span></div>
          <button className="btn-hero" onClick={() => go('home')}>
            <i className="ti ti-search" aria-hidden="true" style={{ fontSize: 14 }}></i> Find a photographer
          </button>
        </div>
        <div className="dtabs">
          {tabs.map(t => (
            <button key={t} className={`dtab${activeTab === t ? ' on' : ''}`} onClick={() => setActiveTab(t)}>{t}</button>
          ))}
        </div>
        <div className="sec-label">Upcoming</div>
        <div className="bk-row">
          <div className="bk-acc up"></div>
          <div className="bk-body">
            <div><div className="bk-name">Karim Benali — Full wedding day</div><div className="bk-det">15 September 2025 · 10:00 · Casablanca</div></div>
            <div className="bk-acts"><span className="pill orange">Confirmed</span><button className="btn-mini btn-mg">Cancel</button></div>
          </div>
        </div>
        <div className="bk-row">
          <div className="bk-acc up"></div>
          <div className="bk-body">
            <div><div className="bk-name">Sara Moussaoui — Portrait session</div><div className="bk-det">3 October 2025 · 14:00 · Rabat</div></div>
            <div className="bk-acts"><span className="pill gray">Pending</span><button className="btn-mini btn-mg">Cancel</button></div>
          </div>
        </div>
        <div className="sec-label">Past bookings</div>
        <div className="bk-row">
          <div className="bk-acc done"></div>
          <div className="bk-body">
            <div><div className="bk-name">Nabil Rahmani — Corporate event</div><div className="bk-det">12 June 2025 · Marrakech</div></div>
            <div className="bk-acts"><span className="pill green">Completed</span><button className="btn-mini btn-me">Leave review</button></div>
          </div>
        </div>
        <div className="bk-row">
          <div className="bk-acc done"></div>
          <div className="bk-body">
            <div><div className="bk-name">Hind Tahiri — Portrait session</div><div className="bk-det">5 May 2025 · Tanger</div></div>
            <div className="bk-acts"><span className="pill green">Completed</span><button className="btn-mini btn-mg">Reviewed</button></div>
          </div>
        </div>
        <div className="bk-row">
          <div className="bk-acc can"></div>
          <div className="bk-body">
            <div><div className="bk-name">Leila Fassi — Events coverage</div><div className="bk-det">20 April 2025 · Fès</div></div>
            <div className="bk-acts"><span className="pill red">Cancelled</span></div>
          </div>
        </div>
      </div>
    </div>
  )
}

function PhotographerPage() {
  const portGrads = [
    'linear-gradient(135deg,#023661,#011f3a)',
    'linear-gradient(135deg,#3F3A42,#2a272d)',
    'linear-gradient(135deg,#1a1f28,#0f1318)',
    'linear-gradient(135deg,#1e2a1a,#111a0d)',
    'linear-gradient(135deg,#2a1a1a,#1a0d0d)',
    'linear-gradient(135deg,#021a30,#010e1a)',
    'linear-gradient(135deg,#1a1a2a,#0d0d1a)',
  ]

  return (
    <div id="pg-photographer">
      <div className="ph-wrap">
        <div className="dash-hd">
          <div className="dash-welcome">Studio — <span>Karim Benali</span></div>
          <div style={{ display: 'flex', gap: 8 }}>
            <button className="btn-mini btn-mg" style={{ padding: '8px 16px' }}>Edit profile</button>
            <button className="btn-me btn-mini" style={{ padding: '8px 16px' }}>Add service</button>
          </div>
        </div>
        <div className="ph-stats">
          <div className="ph-sc"><div className="ph-sn">87</div><div className="ph-sl">Total shoots</div><div className="ph-sd">↑ 12 this month</div></div>
          <div className="ph-sc"><div className="ph-sn" style={{ color: 'var(--ember)' }}>4.9</div><div className="ph-sl">Avg. rating</div><div className="ph-sd">↑ 0.1 vs last month</div></div>
          <div className="ph-sc"><div className="ph-sn">34 500</div><div className="ph-sl">Revenue MAD</div><div className="ph-sd">↑ 18% vs last month</div></div>
          <div className="ph-sc"><div className="ph-sn">5</div><div className="ph-sl">Pending requests</div><div className="ph-sd" style={{ color: '#c97070' }}>↑ 2 new today</div></div>
        </div>
        <div className="ph-grid">
          <div className="ph-panel">
            <div className="ph-ph"><div className="ph-pt">Booking requests</div><span className="pill orange">5 new</span></div>
            <div className="ph-pb">
              {[
                { av: 'AK', bg: 'var(--ocean)', name: 'Amina Khaldi', date: 'Full wedding day · 15 Sep 2025' },
                { av: 'YB', bg: 'var(--slate)', name: 'Youssef Bargach', date: 'Engagement session · 22 Sep 2025' },
                { av: 'FZ', bg: '#1a1f28', name: 'Fatima Zahra', date: 'Portrait session · 3 Oct 2025' },
              ].map((r) => (
                <div key={r.name} className="req-row">
                  <div className="req-av" style={{ background: r.bg }}>{r.av}</div>
                  <div className="req-info"><div className="req-name">{r.name}</div><div className="req-date">{r.date}</div></div>
                  <div className="req-acts"><button className="btn-mini btn-me">Accept</button><button className="btn-mini btn-mg">Decline</button></div>
                </div>
              ))}
            </div>
          </div>
          <div className="ph-panel">
            <div className="ph-ph"><div className="ph-pt">Revenue by category</div></div>
            <div className="ph-pb">
              {[
                { lbl: 'Wedding', w: '88%', val: '76%' },
                { lbl: 'Portrait', w: '40%', val: '14%' },
                { lbl: 'Corporate', w: '25%', val: '7%' },
                { lbl: 'Other', w: '10%', val: '3%' },
              ].map(b => (
                <div key={b.lbl} className="bar-r">
                  <div className="bar-lbl">{b.lbl}</div>
                  <div className="bar-tr"><div className="bar-fl" style={{ width: b.w }}></div></div>
                  <div className="bar-val">{b.val}</div>
                </div>
              ))}
            </div>
          </div>
          <div className="ph-panel">
            <div className="ph-ph"><div className="ph-pt">Portfolio</div><button className="btn-mini btn-mg">Upload</button></div>
            <div className="ph-pb">
              <div className="mini-port">
                {portGrads.map((g, i) => <div key={i} className="mt" style={{ background: g }}></div>)}
                <div className="mt" style={{ background: 'rgba(63,58,66,.25)', border: '1px dashed var(--bd)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                  <i className="ti ti-plus" aria-hidden="true" style={{ fontSize: 18, color: 'var(--mist)' }}></i>
                </div>
              </div>
            </div>
          </div>
          <div className="ph-panel">
            <div className="ph-ph"><div className="ph-pt">Services</div><button className="btn-mini btn-mg">Edit</button></div>
            <div className="ph-pb">
              <div className="serv-item"><div><div className="sname">Full wedding day</div><div className="sdesc">8 hrs</div></div><div className="sprice">8 500 MAD</div></div>
              <div className="serv-item"><div><div className="sname">Half-day</div><div className="sdesc">4 hrs</div></div><div className="sprice">4 800 MAD</div></div>
              <div className="serv-item"><div><div className="sname">Engagement</div><div className="sdesc">2 hrs</div></div><div className="sprice">2 400 MAD</div></div>
              <div className="serv-item"><div><div className="sname">Portrait</div><div className="sdesc">1.5 hrs</div></div><div className="sprice">1 600 MAD</div></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

function AdminPage() {
  const [activeItem, setActiveItem] = useState('Dashboard')

  const sidebarItems = [
    { section: 'Overview', items: [{ icon: 'ti-layout-dashboard', label: 'Dashboard' }] },
    { section: 'Management', items: [
      { icon: 'ti-users', label: 'Users' },
      { icon: 'ti-camera', label: 'Photographers' },
      { icon: 'ti-calendar', label: 'Bookings' },
      { icon: 'ti-star', label: 'Reviews' },
    ]},
    { section: 'Settings', items: [
      { icon: 'ti-tag', label: 'Categories' },
      { icon: 'ti-settings', label: 'Platform' },
    ]},
  ]

  const bars = [
    { day: 'Mon', h: 42 }, { day: 'Tue', h: 56 }, { day: 'Wed', h: 35 },
    { day: 'Thu', h: 68 }, { day: 'Fri', h: 80, peak: true }, { day: 'Sat', h: 74, peak: true }, { day: 'Sun', h: 48 },
  ]

  return (
    <div id="pg-admin">
      <div className="admin-wrap">
        <div className="sidebar">
          <div style={{ padding: '0 1rem .8rem', fontFamily: "'Barlow Condensed',sans-serif", fontSize: 13, fontWeight: 700, letterSpacing: '.1em', textTransform: 'uppercase', color: 'var(--mist)' }}>Admin</div>
          {sidebarItems.map(s => (
            <div key={s.section}>
              <div className="sb-lbl">{s.section}</div>
              {s.items.map(item => (
                <div key={item.label} className={`sb-item${activeItem === item.label ? ' on' : ''}`} onClick={() => setActiveItem(item.label)}>
                  <i className={`ti ${item.icon}`} aria-hidden="true"></i>{item.label}
                </div>
              ))}
            </div>
          ))}
        </div>
        <div className="admin-main">
          <div className="admin-top">
            <div className="admin-title">Dashboard</div>
            <div style={{ fontSize: 12, color: 'var(--mist)' }}>August 2025</div>
          </div>
          <div className="kpi-row">
            <div className="kpi"><div className="kpi-n">3 842</div><div className="kpi-l">Total users</div><div className="kpi-d up">↑ 12% this month</div></div>
            <div className="kpi"><div className="kpi-n">841</div><div className="kpi-l">Photographers</div><div className="kpi-d up">↑ 8 awaiting approval</div></div>
            <div className="kpi"><div className="kpi-n">1 204</div><div className="kpi-l">Bookings this month</div><div className="kpi-d up">↑ 23% vs July</div></div>
            <div className="kpi"><div className="kpi-n" style={{ color: 'var(--ember)' }}>286K</div><div className="kpi-l">Revenue MAD</div><div className="kpi-d up">↑ 19% vs July</div></div>
          </div>
          <div className="a-panels">
            <div className="apanel">
              <div className="ap-h"><div className="ap-t">Bookings — last 7 days</div></div>
              <div style={{ padding: 14 }}>
                <div style={{ display: 'flex', alignItems: 'flex-end', gap: 5, height: 80, paddingBottom: 4 }}>
                  {bars.map(b => (
                    <div key={b.day} style={{ flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
                      <div className={`mb${b.peak ? ' peak' : ''}`} style={{ width: '100%', height: b.h }}></div>
                      <div className="mb-l">{b.day}</div>
                    </div>
                  ))}
                </div>
              </div>
            </div>
            <div className="apanel">
              <div className="ap-h"><div className="ap-t">Pending approvals</div><span className="pill orange">8 pending</span></div>
              <table className="atbl">
                <thead><tr><th>Name</th><th>Specialty</th><th>Actions</th></tr></thead>
                <tbody>
                  <tr><td>Hassan Alaoui</td><td><span className="pill blue">Wedding</span></td><td><div className="act-row"><button className="btn-mini btn-me">Approve</button><button className="btn-mini btn-mg">Reject</button></div></td></tr>
                  <tr><td>Meryem Tahir</td><td><span className="pill blue">Portrait</span></td><td><div className="act-row"><button className="btn-mini btn-me">Approve</button><button className="btn-mini btn-mg">Reject</button></div></td></tr>
                  <tr><td>Omar Bennani</td><td><span className="pill blue">Events</span></td><td><div className="act-row"><button className="btn-mini btn-me">Approve</button><button className="btn-mini btn-mg">Reject</button></div></td></tr>
                </tbody>
              </table>
            </div>
          </div>
          <div className="apanel" style={{ marginBottom: '1.2rem' }}>
            <div className="ap-h"><div className="ap-t">Recent bookings</div><button className="btn-mini btn-mg">View all</button></div>
            <table className="atbl" style={{ tableLayout: 'fixed', width: '100%' }}>
              <thead><tr><th style={{ width: '22%' }}>Client</th><th style={{ width: '22%' }}>Photographer</th><th style={{ width: '18%' }}>Service</th><th style={{ width: '16%' }}>Date</th><th style={{ width: '12%' }}>Amount</th><th style={{ width: '10%' }}>Status</th></tr></thead>
              <tbody>
                <tr><td>Amina Khaldi</td><td>Karim Benali</td><td style={{ color: 'var(--mist)' }}>Full wedding day</td><td style={{ color: 'var(--mist)' }}>15 Sep 2025</td><td style={{ color: 'var(--ember)', fontFamily: "'Barlow Condensed',sans-serif", fontSize: 14, fontWeight: 700 }}>8 500</td><td><span className="pill orange">Pending</span></td></tr>
                <tr><td>Rachid Fassi</td><td>Sara Moussaoui</td><td style={{ color: 'var(--mist)' }}>Portrait</td><td style={{ color: 'var(--mist)' }}>3 Oct 2025</td><td style={{ color: 'var(--ember)', fontFamily: "'Barlow Condensed',sans-serif", fontSize: 14, fontWeight: 700 }}>1 600</td><td><span className="pill green">Confirmed</span></td></tr>
                <tr><td>Zineb Alami</td><td>Nabil Rahmani</td><td style={{ color: 'var(--mist)' }}>Corporate event</td><td style={{ color: 'var(--mist)' }}>28 Aug 2025</td><td style={{ color: 'var(--ember)', fontFamily: "'Barlow Condensed',sans-serif", fontSize: 14, fontWeight: 700 }}>5 200</td><td><span className="pill green">Completed</span></td></tr>
                <tr><td>Khalid Tazi</td><td>Leila Fassi</td><td style={{ color: 'var(--mist)' }}>Engagement</td><td style={{ color: 'var(--mist)' }}>10 Aug 2025</td><td style={{ color: 'var(--ember)', fontFamily: "'Barlow Condensed',sans-serif", fontSize: 14, fontWeight: 700 }}>2 400</td><td><span className="pill red">Cancelled</span></td></tr>
              </tbody>
            </table>
          </div>
          <div className="a-panels">
            <div className="apanel">
              <div className="ap-h"><div className="ap-t">Recent users</div></div>
              <table className="atbl">
                <thead><tr><th>Name</th><th>Role</th><th>Status</th><th></th></tr></thead>
                <tbody>
                  <tr><td>Amina Khaldi</td><td style={{ color: 'var(--mist)' }}>Client</td><td><span className="pill green">Active</span></td><td><div className="act-row"><button className="icon-btn"><i className="ti ti-edit" aria-hidden="true"></i></button><button className="icon-btn" style={{ color: '#c97070' }}><i className="ti ti-ban" aria-hidden="true"></i></button></div></td></tr>
                  <tr><td>Hassan Alaoui</td><td style={{ color: 'var(--mist)' }}>Photographer</td><td><span className="pill orange">Pending</span></td><td><div className="act-row"><button className="icon-btn"><i className="ti ti-edit" aria-hidden="true"></i></button><button className="icon-btn" style={{ color: '#c97070' }}><i className="ti ti-ban" aria-hidden="true"></i></button></div></td></tr>
                  <tr><td>Zineb Alami</td><td style={{ color: 'var(--mist)' }}>Client</td><td><span className="pill green">Active</span></td><td><div className="act-row"><button className="icon-btn"><i className="ti ti-edit" aria-hidden="true"></i></button><button className="icon-btn" style={{ color: '#c97070' }}><i className="ti ti-ban" aria-hidden="true"></i></button></div></td></tr>
                </tbody>
              </table>
            </div>
            <div className="apanel">
              <div className="ap-h"><div className="ap-t">Bookings by category</div></div>
              <div style={{ padding: 14 }}>
                {[
                  { lbl: 'Wedding', w: '82%', val: '512' },
                  { lbl: 'Portrait', w: '45%', val: '281' },
                  { lbl: 'Corporate', w: '30%', val: '187' },
                  { lbl: 'Events', w: '22%', val: '138' },
                  { lbl: 'Product', w: '14%', val: '86' },
                ].map(b => (
                  <div key={b.lbl} className="bar-r">
                    <div className="bar-lbl">{b.lbl}</div>
                    <div className="bar-tr"><div className="bar-fl" style={{ width: b.w }}></div></div>
                    <div className="bar-val">{b.val}</div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

function LoginPage({ go }: { go: (p: Page) => void }) {
  const [email, setEmail] = useState('')
  const [pass, setPass] = useState('')
  const [showPass, setShowPass] = useState(false)
  const [emailErr, setEmailErr] = useState(false)
  const [passErr, setPassErr] = useState(false)
  const [success, setSuccess] = useState(false)

  function submit() {
    let ok = true
    if (!email.trim() || !email.includes('@')) { setEmailErr(true); ok = false } else setEmailErr(false)
    if (!pass) { setPassErr(true); ok = false } else setPassErr(false)
    if (!ok) return
    setSuccess(true)
  }

  return (
    <div id="pg-login">
      <div className="auth-pg">
        <div className="auth-split">
          <div className="auth-left ocean">
            <div className="al-ap"></div>
            <div className="al-logo">Snap<span>Book</span></div>
            <div>
              <div className="al-title">Welcome<br /><em>back.</em></div>
              <p className="al-sub">Book world-class photographers for every moment that matters.</p>
            </div>
            <div className="al-bullets">
              <div className="al-bl"><div className="al-dot"></div>840+ professional photographers</div>
              <div className="al-bl"><div className="al-dot"></div>Instant availability calendar</div>
              <div className="al-bl"><div className="al-dot"></div>Secure online booking</div>
              <div className="al-bl"><div className="al-dot"></div>Verified reviews</div>
            </div>
          </div>
          <div className="auth-right">
            {!success ? (
              <div id="login-form">
                <div className="form-title">Log in</div>
                <p className="form-sub">Access your account to manage bookings</p>
                <div className="fld">
                  <label className="lbl">Email address</label>
                  <input className="inp" type="email" placeholder="you@example.com" value={email} onChange={e => { setEmail(e.target.value); setEmailErr(false) }} />
                  {emailErr && <div className="err-msg">Enter a valid email address.</div>}
                </div>
                <div className="fld">
                  <label className="lbl">Password</label>
                  <div className="iw">
                    <input className="inp" type={showPass ? 'text' : 'password'} placeholder="Your password" value={pass} onChange={e => { setPass(e.target.value); setPassErr(false) }} />
                    <button className="eye-btn" onClick={() => setShowPass(s => !s)} aria-label="Show password">
                      <i className={`ti ${showPass ? 'ti-eye-off' : 'ti-eye'}`} aria-hidden="true"></i>
                    </button>
                  </div>
                  {passErr && <div className="err-msg">Password is required.</div>}
                </div>
                <span className="forgot">Forgot your password?</span>
                <button className="btn-submit" onClick={submit}>Log in</button>
                <div className="or-div"><span>or</span></div>
                <button className="btn-google"><i className="ti ti-brand-google" aria-hidden="true" style={{ fontSize: 16 }}></i>Continue with Google</button>
                <div className="switch-lnk">{"Don't have an account? "}<a onClick={() => go('signup')}>Sign up</a></div>
              </div>
            ) : (
              <div id="login-success" className="success-st">
                <div className="success-ico"><i className="ti ti-check" aria-hidden="true"></i></div>
                <div className="success-t">{"You're in"}</div>
                <p className="success-s">Redirecting you to your dashboard…</p>
                <button className="btn-book" style={{ marginTop: '1rem' }} onClick={() => go('client')}>Go to dashboard</button>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  )
}

function SignupPage({ go }: { go: (p: Page) => void }) {
  const [role, setRole] = useState<'client' | 'photographer' | 'admin'>('client')
  const [fname, setFname] = useState('')
  const [lname, setLname] = useState('')
  const [email, setEmail] = useState('')
  const [pass, setPass] = useState('')
  const [pass2, setPass2] = useState('')
  const [showPass, setShowPass] = useState(false)
  const [showPass2, setShowPass2] = useState(false)
  const [termsOn, setTermsOn] = useState(false)
  const [errs, setErrs] = useState<Record<string, boolean>>({})
  const [success, setSuccess] = useState(false)
  const [strLevel, setStrLevel] = useState(0)

  function checkStr(v: string) {
    let s = 0
    if (v.length >= 8) s++
    if (/[A-Z]/.test(v) && /[0-9]/.test(v)) s++
    if (/[^A-Za-z0-9]/.test(v)) s++
    setStrLevel(s)
  }

  const strColors = ['#c97070', '#a07020', '#5dbf7e']
  const strLabels = ['Weak', 'Fair', 'Strong']

  function submit() {
    const e: Record<string, boolean> = {}
    if (!fname.trim()) e['fname'] = true
    if (!lname.trim()) e['lname'] = true
    if (!email.trim() || !email.includes('@')) e['email'] = true
    if (!pass || pass.length < 8) e['pass'] = true
    if (pass !== pass2) e['pass2'] = true
    if (!termsOn) e['terms'] = true
    setErrs(e)
    if (Object.keys(e).length > 0) return
    setSuccess(true)
  }

  return (
    <div id="pg-signup">
      <div className="auth-pg">
        <div className="auth-split">
          <div className="auth-left slate">
            <div className="al-ap"></div>
            <div className="al-logo">Snap<span>Book</span></div>
            <div>
              <div className="al-title">Join the<br /><em>platform.</em></div>
              <p className="al-sub">Create your account as a client or photographer and start in minutes.</p>
            </div>
            <div className="al-bullets">
              <div className="al-bl"><div className="al-dot"></div>Free to join as a client</div>
              <div className="al-bl"><div className="al-dot"></div>Photographers reviewed within 24h</div>
              <div className="al-bl"><div className="al-dot"></div>Secure payments via escrow</div>
              <div className="al-bl"><div className="al-dot"></div>Cancel anytime</div>
            </div>
          </div>
          <div className="auth-right">
            {!success ? (
              <div id="signup-form">
                <div className="form-title">Create account</div>
                <p className="form-sub">Choose your role to get started</p>
                <div className="role-tgl">
                  {(['client', 'photographer', 'admin'] as const).map(r => (
                    <button key={r} className={`rtab${role === r ? ' on' : ''}`} onClick={() => setRole(r)}>
                      {r.charAt(0).toUpperCase() + r.slice(1)}
                    </button>
                  ))}
                </div>
                <div className="fld-row">
                  <div>
                    <label className="lbl">First name</label>
                    <input className="inp" type="text" placeholder="Amina" value={fname} onChange={e => { setFname(e.target.value); setErrs(p => ({ ...p, fname: false })) }} />
                    {errs.fname && <div className="err-msg">First name is required.</div>}
                  </div>
                  <div>
                    <label className="lbl">Last name</label>
                    <input className="inp" type="text" placeholder="Khaldi" value={lname} onChange={e => { setLname(e.target.value); setErrs(p => ({ ...p, lname: false })) }} />
                    {errs.lname && <div className="err-msg">Last name is required.</div>}
                  </div>
                </div>
                <div className="fld">
                  <label className="lbl">Email address</label>
                  <input className="inp" type="email" placeholder="you@example.com" value={email} onChange={e => { setEmail(e.target.value); setErrs(p => ({ ...p, email: false })) }} />
                  {errs.email && <div className="err-msg">Enter a valid email address.</div>}
                </div>
                {role === 'photographer' && (
                  <div className="fld">
                    <label className="lbl">City</label>
                    <input className="inp" type="text" placeholder="Casablanca" />
                  </div>
                )}
                <div className="fld">
                  <label className="lbl">Password</label>
                  <div className="iw">
                    <input className="inp" type={showPass ? 'text' : 'password'} placeholder="Min. 8 characters" value={pass}
                      onChange={e => { setPass(e.target.value); checkStr(e.target.value); setErrs(p => ({ ...p, pass: false })) }} />
                    <button className="eye-btn" onClick={() => setShowPass(s => !s)} aria-label="Show password">
                      <i className={`ti ${showPass ? 'ti-eye-off' : 'ti-eye'}`} aria-hidden="true"></i>
                    </button>
                  </div>
                  {errs.pass && <div className="err-msg">Password must be at least 8 characters.</div>}
                  {pass && (
                    <>
                      <div className="strength">
                        {[0, 1, 2].map(i => (
                          <div key={i} className="sb-seg" style={{ background: i < strLevel ? strColors[strLevel - 1] : 'rgba(118,130,142,.18)' }}></div>
                        ))}
                      </div>
                      <div className="str-lbl" style={{ color: strLevel > 0 ? strColors[strLevel - 1] : 'var(--mist)' }}>
                        {strLevel > 0 ? strLabels[strLevel - 1] : ''}
                      </div>
                    </>
                  )}
                </div>
                <div className="fld">
                  <label className="lbl">Confirm password</label>
                  <div className="iw">
                    <input className="inp" type={showPass2 ? 'text' : 'password'} placeholder="Repeat password" value={pass2}
                      onChange={e => { setPass2(e.target.value); setErrs(p => ({ ...p, pass2: false })) }} />
                    <button className="eye-btn" onClick={() => setShowPass2(s => !s)} aria-label="Show password">
                      <i className={`ti ${showPass2 ? 'ti-eye-off' : 'ti-eye'}`} aria-hidden="true"></i>
                    </button>
                  </div>
                  {errs.pass2 && <div className="err-msg">{"Passwords don't match."}</div>}
                </div>
                <div className="check-row">
                  <div className={`chk${termsOn ? ' on' : ''}`} onClick={() => { setTermsOn(t => !t); setErrs(p => ({ ...p, terms: false })) }}>
                    {termsOn && <i className="ti ti-check" aria-hidden="true" style={{ fontSize: 11, color: '#fff' }}></i>}
                  </div>
                  <div className="chk-lbl">{"I agree to SnapBook's "}<a href="#">Terms of service</a>{" and "}<a href="#">Privacy policy</a></div>
                </div>
                {errs.terms && <div className="err-msg" style={{ marginTop: '-.6rem', marginBottom: '.8rem' }}>You must accept the terms to continue.</div>}
                <button className="btn-submit" onClick={submit}>Create account</button>
                <div className="or-div"><span>or</span></div>
                <button className="btn-google"><i className="ti ti-brand-google" aria-hidden="true" style={{ fontSize: 16 }}></i>Continue with Google</button>
                <div className="switch-lnk">{"Already have an account? "}<a onClick={() => go('login')}>Log in</a></div>
              </div>
            ) : (
              <div id="signup-success" className="success-st">
                <div className="success-ico"><i className="ti ti-check" aria-hidden="true"></i></div>
                <div className="success-t">Account created</div>
                <p className="success-s">Welcome to SnapBook. Check your email to confirm your address.</p>
                <button className="btn-book" style={{ marginTop: '1rem' }} onClick={() => go('login')}>Log in</button>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  )
}

export default function App() {
  const [page, setPage] = useState<Page>('home')

  function go(p: Page) {
    setPage(p)
    window.scrollTo(0, 0)
  }

  return (
    <div style={{ minHeight: '100vh', background: 'var(--void)' }}>
      <Nav page={page} go={go} />
      {page === 'home' && <HomePage go={go} />}
      {page === 'profile' && <ProfilePage go={go} />}
      {page === 'booking' && <BookingPage go={go} />}
      {page === 'client' && <ClientPage go={go} />}
      {page === 'photographer' && <PhotographerPage />}
      {page === 'admin' && <AdminPage />}
      {page === 'login' && <LoginPage go={go} />}
      {page === 'signup' && <SignupPage go={go} />}
    </div>
  )
}
